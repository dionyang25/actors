<?php

namespace app\Controllers;

use app\Actors\RoomListActor;
use Server\CoreBase\Actor;
use Server\CoreBase\Controller;

/**
 * Created by PhpStorm.
 * User: zhangjincheng
 * Date: 16-7-15
 * Time: 下午3:51
 */
class Action extends Controller
{
    protected function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name);

    }

    public function connect()
    {
        $uid = time();
        $this->bindUid($uid);
        $this->send(['type' => 'welcome','msg'=>'已连接服务器，用户id为'.$uid]);
    }

    /**
     * 进入房间
     */
    public function enterRoom()
    {
            if(!Actor::has('roomList')){
               var_dump( Actor::create(RoomListActor::class,'roomList'));
            }
            return ;
            //列表获取可用房间
            $RoomActorName = Actor::getRpc('roomList')->findAvailableRoom();
            $user_info['id'] = $this->uid;
            if(Actor::getRpc($RoomActorName)->joinRoomReply($user_info)){
                $this->send(['type' => '101', 'msg' => '已进入房间！房间号-'.$RoomActorName,'params'=>['room_no'=>$RoomActorName]]);
            }else{
                $this->send(['type' => '102', 'msg' => '进入房间失败！']);
            }
    }

    public function update()
    {
        $this->sendToAll(
            [
                'type' => 'update',
                'id' => $this->uid,
                'angle' => $this->client_data->angle + 0,
                'momentum' => $this->client_data->momentum + 0,
                'x' => $this->client_data->x + 0,
                'y' => $this->client_data->y + 0,
                'life' => 1,
                'name' => isset($this->client_data->name) ? $this->client_data->name : 'Guest.' . $this->uid,
                'authorized' => false,
            ]);
    }

    public function message()
    {
        $this->sendToAll(
            [
                'type' => 'message',
                'id' => $this->uid,
                'message' => $this->client_data->message,
            ]
        );
    }

    public function onClose()
    {
        $this->destroy();
    }

    public function onConnect()
    {
        $this->destroy();
    }
}
