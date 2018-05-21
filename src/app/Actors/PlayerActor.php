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
     */
    public function initData($user_info){
        $this->saveContext['info'] = $user_info;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

    public function info(){
        return $this->saveContext->getData()['info'];
    }
}