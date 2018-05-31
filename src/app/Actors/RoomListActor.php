<?php
namespace app\Actors;
use Server\Components\Event\EventDispatcher;
use Server\CoreBase\Actor;

class RoomListActor extends Actor{

    public function initData(){
        $this->saveContext['room_list'] = [];
        $this->saveContext['user_room'] = [];
    }

    public function info($roomActorName = '',$style = 0){
        $list = $this->saveContext->getData()['room_list'];
        if(empty($roomActorName)){
            if($style){
                $ret = [];
                foreach ($list as $key=>$val){
                    $ret[] = ['name'=>$key,'num'=>$val];
                }
                return $ret;
            }
           return $list;//只有房间名和人数
        }else{
           return $list;
        }
    }

    public function createRoom($enter = 0,$user_id = ''){
        $time =  time();
        $room_id = $time;
        $RoomActorName = 'roomActorId'.$room_id;
        try{
            Actor::create(RoomActor::class,$RoomActorName);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $room_info['id'] = $RoomActorName;
        $room_info['create_time'] = $time;
        try{
            //创建房间
            Actor::getRpc($RoomActorName)->initData($room_info);
            //推送消息
            if($enter){
                $this->subUserToRoom($RoomActorName,$user_id);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        return $RoomActorName;
    }

    /**
     * 查找可用房间如无可用则创建一个，有可用则返回可用
     */
    public function findAvailableRoom($enter = 0,$user_id = ''){
        if(is_array($this->saveContext['room_list'])){
            foreach ($this->saveContext['room_list'] as $RoomActorName=>$user_num){
                if($user_num<2){
                    if($enter){
                        $this->subUserToRoom($RoomActorName,$user_id);
                    }
                    return $RoomActorName;
                }
            }
        }
        return $this->createRoom($enter,$user_id);
    }

    function registStatusHandle($key, $value)
    {
       return $this->saveContext[$key] == $value;
    }

    public function addRoomUserInfo($RoomActorName,$user_id){
        if(empty($this->saveContext->getData()['room_list'][$RoomActorName])){
            $this->saveContext->getData()['room_list'][$RoomActorName] = 1;
        }else{
            $this->saveContext->getData()['room_list'][$RoomActorName]++;
        }
        $this->saveContext->getData()['user_room'][$user_id] = $RoomActorName;
        //刷新房间列表
        $this->saveContext->save();
        $data = [
            'type'=>'1003',
            'msg'=>'房间列表',
            'params'=>['room_list'=>$this->info('',1)]
        ];
        Actor::getRpc($RoomActorName)->pubMsg($data['type'],$data['msg'],$data['params']);

    }

    public function removeRoomUser($user_id){
        $RoomActorName = $this->saveContext->getData()['user_room'][$user_id];
        $this->saveContext->getData()['room_list'][$RoomActorName]--;
        unset($this->saveContext->getData()['user_room'][$user_id]);

        if(empty($this->saveContext->getData()['room_list'][$RoomActorName])){
            Actor::destroyActor($RoomActorName);
            unset($this->saveContext->getData()['room_list'][$RoomActorName]);
        }
        $this->saveContext->save();
    }

    public function hasRoom($user_id){
        return isset($this->saveContext->getData()['user_room'][$user_id])?$this->saveContext->getData()['user_room'][$user_id]:false;
    }

    /**
     * 推送消息给这个room使其创建用户
     * @param $RoomActorName
     * @throws
     */
    private function subUserToRoom($RoomActorName,$user_id){
        try{
           if(Actor::getRpc($RoomActorName)->joinRoomReply(['id'=>$user_id])){
               //房间列表统计人数+1
               $this->addRoomUserInfo($RoomActorName,$user_id);
           }
        }catch (\Throwable $e){
            echo $e->getMessage();
        }

    }
}