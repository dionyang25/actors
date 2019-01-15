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
     * @return
     */
    public function dealEffect($effect,$origin_uid,$object = null,$duration = null){

        if(!is_array($object) && $object!=null){
            $object = [$object];
        }else{
            return false;
        }
        $msg = '';
        $buff_name = '';
        $buff_msg = $this->config->get('users.buff');
        if(isset($effect['selection'])){
            $buff_name = $buff_msg[$effect['selection']];
        }else if(isset($effect['section'])){
            $buff_name = $buff_msg[$effect['section']];
        }
        foreach ($object as $uid){
            if(!empty($effect['method'])){

                switch ($effect['method']) {
                    case 'clean':
                        $this->clean($effect,$origin_uid,$uid);
                        $msg .= sprintf('%s 的光环 %s 受到净化!',$uid,$buff_name);
                        break;
                    case 'extend':
                        $this->extend($effect,$origin_uid,$uid);
                        $msg .= sprintf('%s 的光环 %s 被延长%s回合!',$uid,$buff_name,$effect['value']);
                        break;
                    case 'copy':
                        $this->copy($effect,$origin_uid,$uid);
                        $msg .= sprintf('%s 获得 %s 的光环 %s !',$origin_uid,$uid,$buff_name);
                        break;
                }

            }else{
                //上buff 有相同的buff 叠加数值 不叠加回合
                $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();

                if(isset($game_info['buff'][$effect['section']][1]) && is_int($game_info['buff'][$effect['section']][1])){
                    //val = -1 debuff

                    $effect['value'] += $game_info['buff'][$effect['section']][1];
                }
                if(isset($effect['duration'])){
                    $duration = $effect['duration'];
                    //循环查找并叠加duration的数值（伤害等)
                    if(isset($game_info['buff'][$effect['section']][2][0])){
                       if($duration[0]['type'] == $game_info['buff'][$effect['section']][2][0]['type']){
                           $duration[0]['value'] += $game_info['buff'][$effect['section']][2][0]['value'];
                       }
                    }
                }
                if(isset($game_info['buff'][$effect['section']][0])){
                    $effect['turns'] = $game_info['buff'][$effect['section']][0];
                }
                $game_info['buff'][$effect['section']] = [$effect['turns'],$effect['value'],$duration];
                Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
                $msg .= sprintf('%s 获得光环 %s ，持续 %s 回合',$uid,$buff_name,$effect['turns']);
            }
        }


        return ['msg'=>$msg];
    }

    /**
     * 持续性效果buff 效果触发
     */
    public function durationBuff($uid,$uids){
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        if(isset($game_info['buff'])){
            $msg = '';
            $buff_msg = $this->config->get('users.buff');
            foreach ($game_info['buff'] as $key=>$val){
                if(isset($val[2]) && is_array($val[2])){
                    //处理持续性buff效果
                    $msg.=sprintf('光环：%s 触发！',$buff_msg[$key]);
                    foreach ($val[2] as $effect){
                        try{
                            Actor::create('app\\Actors\\'.ucfirst($effect['type'].'Actor'),$effect['type']);
                        }catch (\Exception $e){
                            echo $e->getMessage();
                        }
                        $object = Actor::getRpc('cardList-'.$uid)->genObject($effect['object']);
                        Actor::getRpc($effect['type'])->dealEffect($effect,$uid,$object);

                    }
                }
            }
            //计算完 触发一次胜负判定
            try{
                Actor::create(VictoryActor::class,'victory');
            }catch (\Exception $e){

            }
            Actor::getRpc('victory')->judge($uids);
            return ['msg'=>$msg];
        }
        return false;
    }

    /**
     * 净化buff
     */
    public function clean($effect,$origin_uid,$uid){
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        if(empty($effect['selection'])){
            return false;
        }
        if(isset($game_info['buff'][$effect['selection']])){
            unset($game_info['buff'][$effect['selection']]);
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            return true;
        }
        return false;
    }

    /**
     * 延长buff
     */
    public function extend($effect,$origin_uid,$uid){
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        if(empty($effect['selection'])){
            return false;
        }
        if(isset($game_info['buff'][$effect['selection']][0])){
            $game_info['buff'][$effect['selection']][0] += (int)$effect['value'];
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
            return true;
        }
        return false;
    }

    /**
     * 复制buff
     */
    public function copy($effect,$origin_uid,$uid){
        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
        if(empty($effect['selection'])){
            return false;
        }
        if(isset($game_info['buff'][$effect['selection']])){
            $game_info_self = Actor::getRpc('Player-'.$origin_uid)->gameInfo();
            $game_info_self['buff'][$effect['selection']] = $game_info['buff'][$effect['selection']];
            Actor::getRpc('Player-'.$origin_uid)->changeGameInfo($game_info_self);
            return true;
        }
        return false;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}