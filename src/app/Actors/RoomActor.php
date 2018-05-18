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
        $join_users = $this->saveContext['user_list'];
        if(!isset($join_users[$user_id])){
            if(count($join_users)>=self::ROOM_USERS){
                return false;
            }
            $current_user_actor = $this->name . $user_id;
            try{
                Actor::create(PlayerActor::class,$current_user_actor);
                Actor::getRpc($current_user_actor)->initData($user_info);
            }catch (\Exception $e){
                return false;
            }
        }else{
            //重进房间逻辑
        }
        //订阅房间消息
        get_instance()->addSub('Room/'.$this->name,$user_id);
        get_instance()->pub('Room/'.$this->name,$user_id.'进入房间');
        return true;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}