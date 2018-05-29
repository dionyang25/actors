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
        //卡牌列表-发布卡牌信息
        $this->pubCardInfo();
    }

    //打出卡牌
    public function draw($card_order,$object = null){
        //获取卡组信息
        $deck = $this->saveContext->getData()['list'];
        if(!isset($deck[$card_order])){
            return ['res'=>false,'msg'=>'卡组中不存在此卡'];
        }
        //获取该卡属性
        $card_list = $this->config->get('cards');
        $card_desc = $card_list[$deck[$card_order]];
        //判断指向，如未指定，则默认选择对手
        if($card_desc['is_object']){
            if($object = null){

            }
        }
        //依次处理效果

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