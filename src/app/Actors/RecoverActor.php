<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 * 回复 净化相关actor
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class RecoverActor extends Actor{


    public function dealEffect($effect,$origin_uid,$object = null){
        if(!is_array($object) && $object!=null){
            $object = [$object];
        }else{
            //默认回复自己
            $object = [$origin_uid];
        }
        $msg = '';
        //指向回复
        foreach ($object as $uid){
            //获取用户ip
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            $game_info['hp'] += $effect['value'];
            if($game_info['hp']>$this->config['users']['game_initial']['hp']){
                $game_info['hp'] = $this->config['users']['game_initial']['hp'];
            }
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            $msg .= sprintf('%s 回复 %s 点生命！',$uid,$effect['value']);
        }
        return ['msg'=>$msg];

    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}