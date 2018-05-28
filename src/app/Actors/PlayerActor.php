<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/17
 * Time: 15:34
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class PlayerActor extends Actor{
    /**
     * 储存用户信息
     * @param $user_info
     * @throws
     */
    public function initData($user_info){
        $this->saveContext['user_info'] = $user_info;
        //订阅用户消息
        var_dump($user_info['id']);
        get_instance()->addSub('Player/'.$this->name,$user_info['id']);
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

    public function info(){
        return $this->saveContext->getData()['info'];
    }

    public function addGameInfo($init = 0,$game_info=[]){
        if($init){
            $game_info = [
                'hp'=>15,
                'card_num'=>5
            ];
        }
        $this->saveContext->getData()['game_info'] = $game_info;
        $this->saveContext->save();
        //发布游戏信息信息

//        get_instance()->pub('Room/'.$this->name,$data);
        return $game_info;
    }
}