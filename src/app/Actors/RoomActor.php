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
        get_instance()->addSub('Room/'.$this->name,$user_id);
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
        unset($this->saveContext->getData()['user_list'][$user_id]);
        $this->saveContext->save();
        Actor::getRpc('roomList')->removeRoomUser($user_id);
    }

    /**
     * 开始游戏
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function startGame(){
        $num = count($this->saveContext->getData()['user_list']);
        if($num==2){
            $data = [
                'type'=>'1005',
                'msg'=>'游戏开始'
            ];
            get_instance()->pub('Room/'.$this->name,$data);
            $this->initGame();
            return true;
        }else{
            $data = [
                'type'=>105,
                'msg'=>'房间人数不足，无法开始游戏。'
            ];
            get_instance()->pub('Room/'.$this->name,$data);
            return false;
        }
    }

    /**
     * 初始化游戏
     * @throws \Server\Asyn\MQTT\Exception
     */
    public function initGame(){
        //设置下对手
        $opponents[$this->saveContext->getData()['user_list'][0]] = $this->saveContext->getData()['user_list'][1];
        $opponents[$this->saveContext->getData()['user_list'][1]] = $this->saveContext->getData()['user_list'][0];
        //为两边生成游戏数据
        foreach ($this->saveContext->getData()['user_list'] as $uid=>$val){
            $user_info_initial = ['uid'=>$uid,'room'=>$this->name,'opponent'=>$opponents];
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
    }

    /**
     * 发布房间用户游戏信息
     * @throws
     */
    public function pubGameInfo(){
        $game_info = [];
        foreach ($this->saveContext->getData()['user_list'] as $uid=>$val){
            $temp = Actor::getRpc('Player-'.$uid)->gameInfo();
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

    function registStatusHandle($key, $value)
    {
        return false;
    }
}