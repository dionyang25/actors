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
        $msg = '';
        foreach ($object as $uid){
            //获取用户hp
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            //查看目标伤害加深buff
            if(!empty($game_info['buff']['vulnerability'])){
                $dmg_value += $game_info['buff']['vulnerability'][1];
            }
            //查看目标减伤buff
            if(!empty($game_info['buff']['reducer'])){
                $dmg_value -= $game_info['buff']['reducer'][1];
            }
            //最终伤害
            $final_dmg = $effect['value']+$dmg_value;
            if($final_dmg<0){$final_dmg = 0;}
            $game_info['hp'] -= $final_dmg;
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            $msg .= sprintf('对 %s 造成 %s 点伤害！',$uid,$final_dmg);
        }
        //组织msg
        return ['msg'=>$msg];

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}