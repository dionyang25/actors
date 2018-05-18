<?php
namespace app\Actors;
use Server\CoreBase\Actor;

class RoomListActor extends Actor{

    public function initData(){
        $this->saveContext['room_list'] = [];
    }

    public function info(){
        return $this->saveContext['room_list'];//只有房间名和人数
    }

    public function createRoom(){
        $time =  microtime(true)*1000;
        $room_id = $time;
        $RoomActorName = 'roomActorId'.$room_id;
        Actor::create(RoomActor::class,$RoomActorName);
        $room_info['id'] = $RoomActorName;
        $room_info['create_time'] = $time;
        Actor::getRpc($RoomActorName)->initData($room_info);
        $this->saveContext['room_list'][$RoomActorName] = 0;//记录房间人数
        return $RoomActorName;
    }

    /**
     * 查找可用房间如无可用则创建一个
     */
    public function findAvailableRoom(){

        foreach ($this->saveContext['room_list'] as $k=>$v){
            if($v<2){
                return $k;
            }
        }
        return $this->createRoom();
    }

    function registStatusHandle($key, $value)
    {
       return $this->saveContext[$key] == $value;
    }
}