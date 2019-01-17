<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:40
 */
namespace app\Actors;

use app\Models\CardsModel;
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
    public function addNewCard($num = 1,$random = 1,$is_pub_user_info = 1,$is_pub_card_info = 1,$card_id = 0){
        $card_list = $this->loader->model(CardsModel::class,$this)->loadCards(1);
        //随机取卡
        if($random){
            for($i=1;$i<=$num;$i++){
                $key = array_rand($card_list);
                $this->saveContext->getData()['list'][] = $key;
            }
        }else{
            $this->saveContext->getData()['list'][] = $card_id;
        }
        //更新玩家的卡片数量显示
        $game_info = Actor::getRpc($this->saveContext->getData()['user_info']['player'])->gameInfo();
        $game_info['card_num'] += $num;
        //爆牌判定
        if($game_info['card_num']>$this->config['users']['card']['limit']){
            $this->saveContext->getData()['list'] = array_slice($this->saveContext->getData()['list'],0,$this->config['users']['card']['limit']);
            $game_info['card_num'] = $this->config['users']['card']['limit'];
        }
        $this->saveContext->save();
        Actor::getRpc($this->saveContext->getData()['user_info']['player'])->changeGameInfo($game_info);
        if($is_pub_user_info){
            //房间-发布用户信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubGameInfo();
        }
        if($is_pub_card_info){
            //卡牌列表-发布卡牌信息
            $this->pubCardInfo();
            $msg = '玩家'.$this->saveContext->getData()['user_info']['uid'].'抽取'.$num.'张牌！';
            //卡牌列表-发布抽卡信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubMsg('2010',$msg);
        }

    }

    /**
     * 丢弃卡牌
     * @throws
     */
    public function discardCards($num = 1,$is_pub_user_info = 1,$is_pub_card_info = 1){
        for($i = 0;$i<$num;++$i){
            if(empty($this->saveContext->getData()['list'])){
                break;
            }
            $key = array_rand($this->saveContext->getData()['list']);
            unset($this->saveContext->getData()['list'][$key]);
        }
        //减掉卡牌数
        $game_info = Actor::getRpc($this->saveContext->getData()['user_info']['player'])->gameInfo();
        $game_info['card_num'] -=$i;
        Actor::getRpc($this->saveContext->getData()['user_info']['player'])->changeGameInfo($game_info);
        $this->saveContext->save();
        if($is_pub_user_info){
            //房间-发布用户信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubGameInfo();
        }
        if($is_pub_card_info){
            //卡牌列表-发布卡牌信息
            $this->pubCardInfo();
            $msg = '玩家'.$this->saveContext->getData()['user_info']['uid'].'丢弃'.$i.'张牌！';
            //卡牌列表-发布抽卡信息
            Actor::getRpc($this->saveContext->getData()['user_info']['room'])->pubMsg('2010',$msg);
        }
//        $this->saveContext->getData()['list']
    }

    /**
     * 打出卡牌
     * @param $card_order
     * @param null $object
     * @return array
     * @throws
     */
    public function draw($card_order,$object = null,$selection = null){
        //获取卡组信息
        $deck = $this->saveContext->getData()['list'];
        if(!isset($deck[$card_order])){
            return ['res'=>false,'msg'=>'卡组中不存在此卡'];
        }
        //判断是否有打出禁止
        if(!$this->judgeDraw()){
            return ['res'=>false,'msg'=>'无法打出卡牌'];
        }

        //获取该卡属性
        $card_list = $this->loader->model(CardsModel::class,$this)->loadCards();
        $card_desc = $card_list[$deck[$card_order]];
        //资源校验，直接返回扣除后的数据
        if(!Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->checkCardResource($card_desc)){
            //资源不足
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->pubMsg('2010','资源不足');
            return ['res'=>false,'msg'=>'资源不足'];
        }
        //判断指向，如未指定，则默认选择对手
        if($card_desc['is_object']){
            $object = $this->genObject($object);
        }

        $res = false;
        $word3 = '';
        $uids = [$this->saveContext->getData()['user_info']['uid'],$this->saveContext->getData()['user_info']['opponent']];
        //选择器逻辑
        if(!empty($card_desc['selector'])){
            if(is_null($selection)){
                try{
                    Actor::create(SelectorActor::class,'Selector');
                }catch (\Exception $e){
                    echo 'selector:'.$e->getMessage();
                }
                //提前找到需要使用选择器的效果
                foreach ($card_desc['effect'] as $vo){
                    if(empty($vo['selector'])){
                        continue;
                    }
                    //卡牌内部指向判断（处理以内部指向优先）
                    //获取operation code
                    $operation = $object;
                    $object = (isset($vo['object']))?$this->genObject($vo['object']):$object;
                    $selector_data = Actor::getRpc('Selector')->gen($vo,$object);
//                    var_dump('$selector_data',$selector_data);
                    $this->pubSelectorInfo($selector_data,$card_desc['selector'],$card_order,$operation);
                }
                return ['res'=>false,'msg'=>''];
            }else{
                $temp = [];
                foreach ($card_desc['effect'] as $vo){
                    if(!empty($vo['selector'])){
                        $vo['selection'] = $selection;
                    }
                    $temp[] = $vo;
                }
                $card_desc['effect'] = $temp;
            }
        }
//        var_dump('$c_origin',$card_desc['effect']);
        //执行效果
        foreach ($card_desc['effect'] as $vo){
//            var_dump('$card_desc[\'effect\']',$card_desc['effect']);
            try{
//                Actor::create($actors[$vo['type']]['class'],$vo['type']);
                Actor::create('app\\Actors\\'.ucfirst($vo['type'].'Actor'),$vo['type']);
            }catch (\Exception $e){
                echo $e->getMessage();
            }
            //卡牌内部指向判断（处理以内部指向优先）
            $object = (isset($vo['object']))?$this->genObject($vo['object']):$object;
            //处理效果
            $res = Actor::getRpc($vo['type'])->dealEffect($vo,$this->saveContext->getData()['user_info']['uid'],$object);
            $word3 .= $res['msg'];
        }
        if($res){
            //从卡牌列表中移除
            unset($this->saveContext->getData()['list'][$card_order]);
            $this->saveContext->save();
            //计算列表 更新卡牌数
            $game_info['card_num'] = count($this->saveContext->getData()['list']);
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->changeGameInfo($game_info);
            //扣除资源
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->checkCardResource($card_desc,1);
            $modal = '<span style="color:#993300">玩家 %s 打出 %s ,%s</span>';
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
     * @param $card_order string 卡的order
     * @param $resource_order string 这里指的是资源的序号 1-火 2-水 3-风
     * @return array
     * @throws
     */
    public function cover($card_order,$resource_order){
        $uid = $this->saveContext->getData()['user_info']['uid'];
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        //检查buff 已覆盖过则提示已覆盖了
        if(!empty($game_info['buff']['is_cover'])){
            Actor::getRpc('Player-'.$uid)->pubMsg('2010','每回合只能覆盖一张卡');
            return false;
        }
        //获取卡组信息
        $deck = $this->saveContext->getData()['list'];
        if(!isset($deck[$card_order])){
            return ['res'=>false,'msg'=>'卡组中不存在此卡'];
        }
        //获取该卡属性
        $card_list = $this->loader->model(CardsModel::class,$this)->loadCards();
        $card_desc = $card_list[$deck[$card_order]];
        //增加对应资源（搬迁到资源actor去执行)
        $resource_config = $this->config->get('users.resource');
        $increase = isset($card_desc['resource'])?$card_desc['resource']:$resource_config['default_increase'];
        //制造光环
        $effect = [
            'type'=>'resource',
            'method'=>'cover',
            'card_num'=>count($this->saveContext->getData()['list']),
            'object'=>$uid,
            'value'=>[
                $resource_order => $increase
            ]
        ];
        //执行资源增加效果
        try{
//                Actor::create($actors[$vo['type']]['class'],$vo['type']);
            Actor::create('app\\Actors\\'.ucfirst($effect['type'].'Actor'),$effect['type']);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        //处理效果
        $res = Actor::getRpc($effect['type'])->dealEffect($effect,$this->saveContext->getData()['user_info']['uid'],$effect['object']);
        if(!$res){
            Actor::getRpc('Player-'.$uid)->pubMsg('2010','覆盖处理失败');
            return false;
        }
        //增加至覆盖列表@TODO

        //从卡牌列表中移除
        unset($this->saveContext->getData()['list'][$card_order]);
        $this->saveContext->save();

        //发布资源消息
        $modal = '<span style="color:#000099">玩家'.$uid.'覆盖一张卡。'.$res['msg'].'</span>';
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
        $card_list = $this->loader->model(CardsModel::class,$this)->loadCards();
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
     * 升级卡牌 或者类卡牌转换逻辑
     * @param null $card_order
     * @throws
     */
    public function upgrade($card_order = null){
        //获取升级配置
        $level_up = $this->config->get('users.level_up');
        //升级！
        foreach ($this->saveContext->getData()['list'] as $key => &$vo){
            if(!is_null($card_order)){
                //只升级单卡
                if($card_order!=$key){
                    continue;
                }
            }
            if(array_key_exists($vo,$level_up)){
                $vo = $level_up[$vo];
            }
        }
        //发布
//        $this->pubCardInfo();
        $this->saveContext->save();
    }

    /**
     * 发布卡牌信息
     * @throws
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

    /**
     * 发布选择器信息
     * @throws
     */
    public function pubSelectorInfo($data,$selector,$card_order,$operation){
        $words = [1=>'手牌',2=>'光环',3=>'卡牌'];
        $res = [
            'type'=>'2003',
            'msg'=>'选择器信息',
            'params'=>['data'=>$data,'selector'=>$selector,
                'msg'=>'请选择'.$words[$selector],
                'card_order'=>$card_order,
                'operation'=>$operation
            ]
        ];
        get_instance()->pub('Player/'.$this->saveContext->getData()['user_info']['player'],$res);
    }

    /**
     * 增加卡牌计数 可传负数
     */
    public function addCardNum($uid,$num=-1){
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        $game_info['card_num'] +=$num;
        if($game_info['card_num']<0){$game_info['card_num'] = 0;}
        return Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
    }

    /**
     * 指向判定
     * @param string $object  12-对手 13-自己
     */
    public function genObject($object){
        switch ($object){
            case 12:
                $object = $this->saveContext->getData()['user_info']['opponent'];
                break;
            case 13:
                $object = $this->saveContext->getData()['user_info']['uid'];
                break;
        }
        return $object;
    }

    /**
     * 卡牌打出时的判定
     */
    protected function judgeDraw(){
        $game_info = Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->gameInfo();
        //无法打出卡牌
        if(!empty($game_info['buff']['restrict_draw'])){
            Actor::getRpc('Player-'.$this->saveContext->getData()['user_info']['uid'])->pubMsg('2010','由于打出限制，无法出牌');
           return false;
        }
        return true;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}