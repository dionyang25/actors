<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class DmgActor extends Actor{

    function registStatusHandle($key, $value)
    {
        return false;
    }
}