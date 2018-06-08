<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 * buff debuff的卡牌 相关actor
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class BuffActor extends Actor{

    /**
     * 处理buff效果
     * @param $effect
     * @param $origin_uid
     * @param null $object
     * @return bool
     */
    public function dealEffect($effect,$origin_uid,$object = null){

        if(!is_array($object) && $object!=null){
            $object = [$object];
        }else{
            return false;
        }
        foreach ($object as $uid){
            //上buff 有相同的buff 叠加数值 不叠加回合
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            if(isset($game_info['buff'][$effect['section']][1]) && is_int($game_info['buff'][$effect['section']][1])){
                $effect['value'] += $game_info['buff'][$effect['section']][1];
            }
            $game_info['buff'][$effect['section']] = [$effect['turns'],$effect['value']];
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            //自己减少一张手牌计数
            Actor::getRpc('cardList-'.$origin_uid)->addCardNum($origin_uid,-1);
            return true;
        }
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}