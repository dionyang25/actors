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


    public function dealEffect($effect,$origin_uid,$object = null){
        if(!is_array($object) && $object!=null){
            $object = [$object];
            //指向伤害
            foreach ($object as $uid){
                //获取用户ip
                $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
                $game_info['hp'] -= $effect['value'];
                Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            }
            //自己减少一张手牌
            $game_info = Actor::getRpc('Player-'.$origin_uid)->gameInfo();
            $game_info['card_num'] -=1;
            if($game_info['card_num']<0){$game_info['card_num'] = 0;}
            Actor::getRpc('Player-'.$origin_uid)->changeGameInfo($game_info);
            return true;
        }

        return false;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}