<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:40
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class CardListActor extends Actor{

    public function initData($user_info){
        $this->saveContext['user_info'] = $user_info;
        $this->saveContext['list'] = [];//卡组列表
        $this->saveContext['cover'] = [];//覆盖卡的列表
    }

    /**
     * 添加卡牌
     * @param int $num
     * @param int $random
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function addNewCard($num = 1,$random = 1,$is_pub_user_info = 1,$is_pub_card_info = 1){
        $card_list = $this->config->get('cards');
        //随机取卡
        if($random){
            for($i=1;$i<=$num;$i++){
                $key = array_rand($card_list);
                $this->saveContext->getData()['list'][] = $key;
            }
            $this->saveContext->save();
        }
        //更新玩家的卡片数量显示
        $game_info = Actor::getRpc($this->saveContext->getData()['user_info']['player'])->gameInfo();
        $game_info['card_num'] += $num;
        Actor::getRpc($this->saveContext->getData()['user_info']['player'])->changeGameInfo($game_info);
        if($is_pub_user_info){
            //房间-发布用户信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubGameInfo();
        }
        if($is_pub_card_info){
            //卡牌列表-发布卡牌信息
            $this->pubCardInfo();
            $msg = '玩家'.$this->saveContext->getData()['user_info']['uid'].'抽了'.$num.'张卡！';
            //卡牌列表-发布抽卡信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubMsg('2010',$msg);
        }

    }


    /**
     * 打出卡牌
     * @param $card_order
     * @param null $object
     * @return array
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function draw($card_order,$object = null){
        //获取卡组信息
        $deck = $this->saveContext->getData()['list'];
        if(!isset($deck[$card_order])){
            return ['res'=>false,'msg'=>'卡组中不存在此卡'];
        }
        //获取该卡属性
        $card_list = $this->config->get('cards');
        $card_desc = $card_list[$deck[$card_order]];
        //资源校验，直接返回扣除后的数据
        if(!Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->checkCardResource($card_desc)){
            //资源不足
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->pubMsg('2010','资源不足');
            return ['res'=>false,'msg'=>'资源不足'];
        }
        //判断指向，如未指定，则默认选择对手
        if($card_desc['is_object']){
            if($object == null){
                $object = $this->saveContext->getData()['user_info']['opponent'];
            }
        }
        //依次处理效果
        $actors = ['dmg'=>['class'=>DmgActor::class,'msg'=>'对 %s 造成 %s 点伤害！']];
        $res = false;
        $word3 = '';
        $uids = [$this->saveContext->getData()['user_info']['uid'],$this->saveContext->getData()['user_info']['opponent']];
        foreach ($card_desc['effect'] as $vo){
            try{
                Actor::create(DmgActor::class,$vo['type']);
            }catch (\Exception $e){

            }
            //处理效果
            $res = Actor::getRpc($vo['type'])->dealEffect($vo,$this->saveContext->getData()['user_info']['uid'],$object);
            $word3 .= sprintf($actors[$vo['type']]['msg'],$object,$vo['value']);
        }
        if($res){
            //从卡牌列表中移除
            unset($this->saveContext->getData()['list'][$card_order]);
            $this->saveContext->save();
            //扣除资源
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->checkCardResource($card_desc,1);
            $modal = '玩家 %s 打出 %s ,%s!';
            $modal = sprintf($modal,$this->saveContext->getData()['user_info']['uid'],$card_desc['name'],$word3);
            //发布卡牌效果消息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubMsg('2010',$modal);
            //房间-发布用户信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubGameInfo();
            //卡牌列表-发布卡牌信息
            $this->pubCardInfo();
            //胜负逻辑判定
            try{
                Actor::create(VictoryActor::class,'victory');
            }catch (\Exception $e){

            }
            Actor::getRpc('victory')->judge($uids);
            return ['res'=>true,'msg'=>''];
        }else{
            return ['res'=>false,'msg'=>''];
        }
    }

    /**
     * 覆盖卡牌
     * @param $card_order
     * @param $operation
     * @return array
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function cover($card_order,$operation){

        $game_info = Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->gameInfo();
        //检查buff 已覆盖过则提示已覆盖了
        if(!empty($game_info['buff']['is_cover'])){
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->pubMsg('2010','每回合只能覆盖一张卡');
            return false;
        }
        //获取卡组信息
        $deck = $this->saveContext->getData()['list'];
        if(!isset($deck[$card_order])){
            return ['res'=>false,'msg'=>'卡组中不存在此卡'];
        }
        //获取该卡属性
        $card_list = $this->config->get('cards');
        $card_desc = $card_list[$deck[$card_order]];
        //增加对应资源
        $resource_config = $this->config->get('users.resource');
        $increase = isset($card_desc['resource'])?$card_desc['resource']:$resource_config['default_increase'];
        $game_info['resource'][$operation] += $increase;
        //增加至覆盖列表@TODO

        //从卡牌列表中移除
        unset($this->saveContext->getData()['list'][$card_order]);
        $this->saveContext->save();
        //减少一张卡
        $game_info['card_num']--;
        //增加buff
        $game_info['buff']['is_cover'] = [1,[]];
        Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->changeGameInfo($game_info);
        //发布资源消息
        $modal = '玩家'.$this->saveContext->getData()['user_info']['uid'].'覆盖一张卡,'.$resource_config[$operation].'属性资源+'.$increase;
        Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubMsg('2010',$modal);
        //房间-发布用户信息
        Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubGameInfo();
        //卡牌列表-发布卡牌信息
        $this->pubCardInfo();
        return true;
    }

    //返回所有卡的属性
    public function fetchList(){
        $list = $this->saveContext->getData()['list'];
        $card_list = $this->config->get('cards');
        $ret = [];
        foreach ($list as $key =>$vo){
           $ret[] = [
               'card_order'=>$key,
               'card_id'=>$vo,
               'card_desc'=>$card_list[$vo]
           ];
        }
        return $ret;
    }

    /**
     * 发布卡牌信息
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function pubCardInfo(){
        $card_list = $this->fetchList();
        $data = [
            'type'=>'2002',
            'msg'=>'卡牌信息',
            'params'=>['card_info'=>$card_list]
        ];
        get_instance()->pub('Player/'.$this->saveContext->getData()['user_info']['player'],$data);
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}