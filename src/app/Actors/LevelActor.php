<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/26
 * Time: 下午5:50
 */

namespace app\Actors;


use Server\CoreBase\Actor;

class LevelActor extends Actor
{
    /**
     * 卡牌升级！效果
     * @param $effect
     * @param $origin_uid
     * @param null $object
     * @return bool
     *  [
            'type'=>'level',
            'method'=>'up',
            'card'=>'all',
            'object'=>13,//表示自己
            'value'=>1
        ]
     *
     */
    public function dealEffect($effect,$origin_uid,$object = null){
        switch($effect['method']){
            case 'up':

                break;
        }
    }

    /**
     * 升级所有卡牌
     */
    public function levelUpAll(){

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}