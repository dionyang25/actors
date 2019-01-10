<?php

namespace app\Controllers;

use app\Actors\RandomNameActor;
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
        try {
            //创建名字
            Actor::create(RandomNameActor::class, 'randomName');
        }catch (\Exception $e){

        }
    }

    /**
     * @throws \Exception
     */
    public function connect()
    {
        if (empty($_SESSION['user'])){
            return $this->send(['type' => '130','msg'=>'请先登录']);
        }
//        $uid = Actor::getRpc('randomName')->getName();
        $uid = $_SESSION['user'];
        $this->bindUid($uid);
        $this->send(['type' => '9001','msg'=>'欢迎 '.$uid.' 连接服务器!','params'=>['uid'=>$uid]]);
    }

    /**
     * 创建房间
     */
    public function createRoom($room = ''){
        if(empty($room)){
            $this->send(['type' => '103', 'msg' => '房间名不能为空']);
            return ;
        }
        $res = Actor::getRpc('roomList')->createRoom(1,$this->uid,$room);
        if(!$res){
            $this->send(['type' => '103', 'msg' => '创建失败']);
            return ;
        }
    }

    /**
     * 进入房间
     */
    public function enterRoom($room = '')
    {

        try{
            //判断是否已在某个房间
            $RoomActorName = Actor::getRpc('roomList')->hasRoom($this->uid);
            if($RoomActorName){
                $this->send(['type' => '103', 'msg' => '您已经在房间'.$RoomActorName.'里了']);
                return ;
            }
            //进入指定房间
            if(!empty($room)){
                $res = Actor::getRpc('roomList')->subUserToRoom($room,$this->uid);
                if(!$res){
                    $this->send(['type' => '103', 'msg' => '进入房间失败']);
                    return ;
                }
                $this->send(['type' => '1001', 'msg' => '进入房间！房间号-'.$room,'params'=>['room_no'=>$room]]);
                return ;
            }
            //快速进入房间
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

    /**
     * 出牌
     * @param $card_order
     * @paramk $operation object 11-不取对象 12-对手 13-自己
     */
    public function drawCard($card_order,$operation = 0){
       //打出卡牌
        try{
            $card_list_name = 'cardList-'.$this->uid;
            $result = Actor::getRpc($card_list_name)->draw($card_order,$operation);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * 覆盖卡牌 TODO
     * @param $card_order
     */
    public function coverCard($card_order,$operation){
        //打出卡牌
        try{
            $card_list_name = 'cardList-'.$this->uid;
            $result = Actor::getRpc($card_list_name)->cover($card_order,$operation);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * 回合结束
     */
    public function endTurn(){
        try{
            //找roomActor
            $RoomActorName = Actor::getRpc('roomList')->hasRoom($this->uid);
            if (!$RoomActorName) {
                $this->send(['type' => '104', 'msg' => '您不在房间内']);
                return;
            }
            //回合结束
            Actor::getRpc($RoomActorName)->endTurn($this->uid,1);


        }catch (\Exception $e){

        }
    }

    public function onClose()
    {
        //支持断线重连

        $this->exitRoom();
        $this->destroy();
    }

    private function getRandomName(){

    }

//    public function onConnect()
//    {
//        $this->destroy();
//    }
}
