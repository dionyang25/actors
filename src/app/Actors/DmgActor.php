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
        //查看自身伤害增益buff
        $dmg_value = Actor::getRpc('Player-'.$origin_uid)->getBuffInfo('dmg');
        if(empty($dmg_value)){$dmg_value = 0;}
        //指向伤害
        foreach ($object as $uid){
            //获取用户hp
            //查看对手伤害加深buff
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            $game_info['hp'] -= ($effect['value']+$dmg_value);
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
        }
        
        return true;

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}