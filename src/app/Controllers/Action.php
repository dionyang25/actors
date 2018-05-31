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
        $this->send(['type' => '9001','msg'=>'已连接服务器，用户id为'.$uid,'params'=>['uid'=>$uid]]);
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

    /**
     * 退房
     */
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
            $this->roomList();
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * 房间列表
     */
    public function roomList(){
          try {
              $list = Actor::getRpc('roomList')->info('',1);
              $this->send(['type' => '1003', 'msg' => '房间列表：','params'=>[
                  'room_list'=>$list
              ]]);
          }catch (\Exception $e){
              echo $e->getMessage();
          }
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
            //开始游戏
            Actor::getRpc($RoomActorName)->startGame();


        }catch (\Exception $e){

        }

    }

    public function drawCard($card_order){
       //打出卡牌
        try{
            $card_list_name = 'cardList-'.$this->uid;
            $result = Actor::getRpc($card_list_name)->draw($card_order);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    public function onClose()
    {
        $this->exitRoom();
        $this->destroy();
    }

//    public function onConnect()
//    {
//        $this->destroy();
//    }
}
