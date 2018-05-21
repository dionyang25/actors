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
            'type'=>1002,
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
            'type'=>1002,
            'msg'=>'系统消息：'.$user_id.'已退出房间'
        ];
        get_instance()->pub('Room/'.$this->name,$data);
        get_instance()->removeSub('Room/'.$this->name,$user_id);
        unset($this->saveContext->getData()['user_list'][$user_id]);
        $this->saveContext->save();
        Actor::getRpc('roomList')->removeRoomUser($user_id);
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}