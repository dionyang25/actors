<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:40
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class CardListActor extends Actor{


    public function addNewCard($num = 1){

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

}