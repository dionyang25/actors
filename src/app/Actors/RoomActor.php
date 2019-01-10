<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/17
 * Time: 10:40
 */
namespace app\Actors;
use Server\CoreBase\Actor;

class RoomActor extends Actor{
    CONST ROOM_USERS = 2;//房间人数
//    private $name = 'current_user';
    /**
     * 初始化房间
     * @param $room_info
     */
    public function initData($room_info){
        $this->saveContext['info'] = $room_info;
    }



    /**
     * 进房询问
     * @param $user_info
     * @throws
     */
    public function joinRoomReply($user_info){
        $user_id = $user_info['id'];
        if(empty($this->saveContext->getData()['user_list'])){
            $this->saveContext->getData()['user_list'] = [];
            $this->saveContext->save();
        }
        $join_users = $this->saveContext->getData()['user_list'];
        if(!isset($join_users[$user_id])){
            if(count($join_users)>=self::ROOM_USERS){
                return false;
            }
            $current_user_actor = 'Player'.$user_id;
            try{
                Actor::create(PlayerActor::class,$current_user_actor);
                Actor::getRpc($current_user_actor)->initData($user_info);
            }catch (\Exception $e){

            }

        }else{
            //重进房间逻辑
        }
        //添加用户
        $this->saveContext->getData()['user_list'][$user_id] = 1;
        $this->saveContext->save();
        //订阅房间消息
        get_instance()->addSub('Player/'.$this->name,$user_id);
        $data = [
            'type'=>'1002',
            'msg'=>'系统消息：欢迎'.$user_id.'进入房间'
        ];
        get_instance()->pub('Room/'.$this->name,$data);
        return true;
    }

    /**
     * 退出房间
     * @throws
     */
    public function exitRoom($user_id){
        $data = [
            'type'=>'1002',
            'msg'=>'系统消息：'.$user_id.'已退出房间'
        ];
        get_instance()->pub('Room/'.$this->name,$data);
        get_instance()->removeSub('Room/'.$this->name,$user_id);
        //循环销毁游戏数据
        $this->exitGame();
        //销毁数据
        unset($this->saveContext->getData()['user_list'][$user_id]);
        $this->saveContext->save();
        Actor::getRpc('roomList')->removeRoomUser($user_id);
    }

    /**
     * 退出游戏
     * @throws
     */
    public function exitGame()
    {
        foreach ($this->saveContext->getData()['user_list'] as $uid=>$val){
            get_instance()->removeSub('Player/Player-'.$uid,$uid);
            //判断是否回合开始
            if(isset($this->saveContext->getData()['game_info'])) {
                try {
                    Actor::destroyActor('Player-' . $uid);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            if(isset($this->saveContext->getData()['game_info']['turn'])) {
                try{
                    Actor::destroyActor('cardList-'.$uid);
                }catch (\Exception $e){
                    echo $e->getMessage();
                }
            }
        }
        //消除回合数据
        unset($this->saveContext->getData()['game_info']);
        $this->saveContext->save();
    }

    /**
     * 开始游戏
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function startGame(){
        $num = count($this->saveContext->getData()['user_list']);
        if($num==2){
            if(!empty($this->saveContext->getData()['game_info']['turn'])){
                $this->pubMsg(205,'游戏已经开始了。');
                return false;
            }
            $this->pubMsg(1005,'游戏开始');
            $this->initGame();
            return true;
        }else{
            $this->pubMsg(105,'房间人数不足，无法开始游戏。');
            return false;
        }
    }

    /**
     * 初始化游戏
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function initGame(){
        //设置下对手
        $uids = array_keys($this->saveContext->getData()['user_list']);
        $opponents[$uids[0]] = $uids[1];
        $opponents[$uids[1]] = $uids[0];
        //为两边生成游戏数据
        foreach ($uids as $uid){
            $user_info_initial = ['uid'=>$uid,'room'=>$this->name,'opponent'=>$opponents[$uid]];
            //生成初始游戏数据
            try{
                Actor::create(PlayerActor::class,'Player-'.$uid);
            }catch (\Exception $e){

            }
            Actor::getRpc('Player-'.$uid)->initData($user_info_initial);
            //先后攻决定

            Actor::getRpc('Player-'.$uid)->addGameInfo(1);
            //生成卡组
            $card_list_name = 'cardList-'.$uid;
            try{
                Actor::create(CardListActor::class,$card_list_name);
            }catch (\Exception $e){

            }
            $user_info_initial['player'] = 'Player-'.$uid;
            Actor::getRpc($card_list_name)->initData($user_info_initial);
            //生产卡牌数据
            //添加5张卡至手卡
            Actor::getRpc($card_list_name)->addNewCard(5,1,0);
        }
        $this->pubGameInfo();
        //开始回合
        $this->startTurn(1);
    }

    /**
     * 发布房间用户游戏信息
     * @throws
     */
    public function pubGameInfo(){
        $game_info = [];
        foreach ($this->saveContext->getData()['user_list'] as $uid=>$val){
            $temp = Actor::getRpc('Player-'.$uid)->gameInfo();
            if(!empty($temp['buff'])){
                $temp['buff'] = $this->dealBuffInfo($temp['buff']);
            }
            $game_info[] = [
                'info'=>$temp,
                'uid'=>$uid
            ];
        }
        $data = [
            'type'=>'2001',
            'msg'=>'用户信息',
            'params'=>['game_info'=>$game_info]
        ];
        get_instance()->pub('Room/'.$this->name,$data);
    }

    /**
     * 发布房间信息
     * @throws
     */
    public function pubMsg($type,$msg,$params = []){
        $data = [
            'type'=>(string)$type,
            'msg'=>$msg,
            'params'=>$params
        ];
        get_instance()->pub('Room/'.$this->name,$data);
    }

    /**
     * 回合开始阶段(turn 指一人一回合)
     * @param $first 是否第一回合
     * @param $is_add_card 是否抽卡 >0表示要抽的卡数
     * @return bool
     */
    public function startTurn($first = 0,$is_add_card=1)
    {
        if(empty($this->saveContext->getData()['game_info']['turn'])){
            $this->saveContext->getData()['game_info']['turn'] = 0;
        }
        //加一回合
        $this->saveContext->getData()['game_info']['turn']++;
        if($first){
            $this->saveContext->getData()['game_info']['turn'] = 1;
            //随机决定先后手
            $turn_uid = array_rand($this->saveContext->getData()['user_list']);
        }else{
            //交换先后手
            $user_list = array_keys($this->saveContext->getData()['user_list']);
            foreach ($user_list as $vo){
                if($this->saveContext->getData()['game_info']['turn_player']!=$vo){
                    $turn_uid = $vo;
                    break;
                }
            }
        }
        $this->saveContext->getData()['game_info']['turn_player'] = $turn_uid;

        //循环发布回合信息
        foreach (array_keys($this->saveContext->getData()['user_list']) as $uid){
            $is_turn_player = ($turn_uid == $uid)?1:0;
            //发布信息
            Actor::getRpc('Player-'.$uid)->pubMsg(2011,'玩家'.$turn_uid.'回合开始！',
                ['turn'=>$this->saveContext->getData()['game_info']['turn'],'is_turn_player'=>$is_turn_player]);
            //轮到回合的 抽卡
            if($is_add_card>0 && $is_turn_player){
                try{
                    Actor::getRpc('cardList-'.$uid)->addNewCard($is_add_card);
                }catch (\Exception $e){
                    $e->getMessage();
                }

            }
        }
        //回合结束时，判断持续性光环
        try{
            Actor::create(BuffActor::class,'buff');
        }catch (\Exception $e){
            $e->getMessage();
        }
        $this->saveContext->save();
    }

    /**
     * 回合结束
     */
    public function endTurn($uid,$begin_new_turn = 0){

        //结算该玩家回合结束时的buff
        $duration_buff = Actor::getRpc('buff')->durationBuff($uid,array_keys($this->saveContext->getData()['user_list']));
        $msg = '';
        if($duration_buff && !empty($duration_buff['msg'])){
            $msg.= $duration_buff['msg']."\n";
        }
        //buff回合数-1
        Actor::getRpc('Player-'.$uid)->calcBuff();

        //发布回合结束文字
        $this->pubMsg(2010,$msg.'第'.$this->saveContext->getData()['game_info']['turn'].'回合结束');
        if($begin_new_turn){
            //开启新的回合
            $this->startTurn();
        }
    }

    /**
     * 整合buff信息
     */
    private function dealBuffInfo($buff){
        if(empty($buff)){
            return [];
        }
        $res = [];
        $name = $this->config->get('users.buff');
        foreach ($buff as $key=>$val){
            if(isset($val[2][0]['value'])){
                $val[1] = $val[2][0]['value'];
            }
            $res[]=[
              'type'=>$key,
              'name'=>$name[$key],
              'turns'=>$val[0],
              'value'=>$val[1]>0?'+'.$val[1]:$val[1]
            ];
        }
        return $res;
    }

    /**
     * 发布回合信息
     * @param $key
     * @param $value
     * @return bool
     */
    public function pubTurnInfo(){
        $turn_uid = $this->saveContext->getData()['game_info']['turn_player'];
        //循环发布回合信息
        foreach (array_keys($this->saveContext->getData()['user_list']) as $uid){
            $is_turn_player = ($turn_uid == $uid)?1:0;
            //发布信息
            Actor::getRpc('Player-'.$uid)->pubMsg('2011','玩家'.$turn_uid.'回合开始！',
                ['turn'=>$this->saveContext->getData()['game_info']['turn'],'is_turn_player'=>$is_turn_player]);
        }
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}