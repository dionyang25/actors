<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 * 卡牌伤害actor
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class DmgActor extends Actor{


    public function dealEffect($effect,$origin_uid,$object = null){
        if(!is_array($object) && $object!=null){
            $object = [$object];
        }else{
            return false;
        }
        //指向伤害
        foreach ($object as $uid){
            //获取用户ip
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            $game_info['hp'] -= $effect['value'];
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
        }
        //自己减少一张手牌计数
        Actor::getRpc('cardList-'.$origin_uid)->addCardNum($origin_uid,-1);
        return true;

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}