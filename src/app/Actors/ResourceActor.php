<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2019/1/14
 * Time: 下午5:34
 */

namespace app\Actors;


use Server\CoreBase\Actor;

class ResourceActor extends Actor
{
    public function dealEffect($effect,$origin_uid,$object = null)
    {
        if (!is_array($object) && $object != null) {
            $object = [$object];
        } else {
            return false;
        }

        //指向资源增加/减少
        $msg = '';
        $config_resource = $this->config->get('users.resource');
        //检查资源增益buff
        $buff_value = 0;
        if(!empty($game_info['buff']['cover'])){
            $buff_value += $game_info['buff']['cover'][1];
        }
        foreach ($object as $uid){
            //获取用户hp
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            //处理覆盖
            if(!empty($effect['method'])){
                switch ($effect['method']){
                    case 'cover':
                        if(isset($effect['card_num'])){
                            $game_info['card_num'] = $effect['card_num'] - 1;
                            if($game_info['card_num'] < 0){
                                $game_info['card_num'] = 0;
                            }
                        }
                        //增加buff
                        $game_info['buff']['is_cover'] = [1,''];
                        break;
                }
            }
            foreach ($effect['value'] as $key =>$val ){
                $val = $val + $buff_value;
                $game_info['resource'][$key] += $val;
                //最低为0
                if($game_info['resource'][$key] < 0){
                    $game_info['resource'][$key] = 0;
                }
                $msg .= sprintf('%s 的%s属性资源增加 %s 点！',$uid,$config_resource[$key],$val);
            }
            Actor::getRpc('Player-'.$uid)->changeGameInfo($game_info);
        }
        //组织msg
        return ['msg'=>$msg];
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}