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
        //基本Actor
        try {
            //创建房间
            Actor::create(RoomListActor::class, 'roomList');
            Actor::getRpc('roomList')->initData();
        }catch (\Exception $e){

        }
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

        try{
            //判断是否已在房间
            $RoomActorName = Actor::getRpc('roomList')->hasRoom($this->uid);
            if($RoomActorName){
                $this->send(['type' => '103', 'msg' => '您已经在房间'.$RoomActorName.'里了']);
                return ;
            }
            //列表获取可用房间
            $RoomActorName = Actor::getRpc('roomList')->findAvailableRoom(1,$this->uid);
            if(!empty($RoomActorName)){
                $this->send(['type' => '1001', 'msg' => '进入房间！房间号-'.$RoomActorName,'params'=>['room_no'=>$RoomActorName]]);
            }else{
                $this->send(['type' => '102', 'msg' => '进入房间失败！']);
            }

        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    public function exitRoom(){
        try {
            //判断是否已在房间
            $RoomActorName = Actor::getRpc('roomList')->hasRoom($this->uid);
            if (!$RoomActorName) {
                $this->send(['type' => '104', 'msg' => '您不在房间内']);
                return;
            }
            //交给RoomActor退房
            Actor::getRpc($RoomActorName)->exitRoom($this->uid);
            $this->send(['type' => '1004', 'msg' => '您已退出房间']);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

      public function roomList(){
          try {
              $list = Actor::getRpc('roomList')->info();
              $this->send(['type' => '1003', 'msg' => '房间列表：','params'=>['room_list'=>$list]]);
          }catch (\Exception $e){
              echo $e->getMessage();
          }
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



    /**
     * 开始游戏
     */
    public function startGame(){
        try{
            //找roomActor
            $RoomActorName = Actor::getRpc('roomList')->hasRoom($this->uid);
            if (!$RoomActorName) {
                $this->send(['type' => '104', 'msg' => '您不在房间内']);
                return;
            }
            //是否人满
            $num = Actor::getRpc('roomList')->info($RoomActorName);
            if($num==2){
                //游戏流程
                $this->send(['type' => '1005', 'msg' => '游戏开始']);
                $this->gameProcess();
            }else{
                $this->send(['type' => '105', 'msg' => '房间人数不足，无法开始游戏。']);
            }

            //成功则进入流程
        }catch (\Exception $e){

        }

    }

    /**
     * 游戏流程
     */
    public function gameProcess(){

    }

    public function onClose()
    {
        //删除用户对应的所有信息
        try{
            Actor::destroyActor('Player'.$this->uid);
            //在房间则退房
            $this->exitRoom();
        }catch (\Exception $e){
            echo $e->getMessage();
        }

        $this->destroy();
    }

//    public function onConnect()
//    {
//        $this->destroy();
//    }
}
