<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:40
 * 和卡牌有关的卡牌效果
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class CardActor extends Actor{

    public function initData($card_info){
        $this->saveContext['card_info'] = $card_info;
    }

    public function useCard($obj){

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

}