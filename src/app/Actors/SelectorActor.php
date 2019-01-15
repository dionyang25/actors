<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2019/1/11
 * Time: 下午2:59
 */

namespace app\Actors;


use Server\CoreBase\Actor;

class SelectorActor extends Actor
{

    /**
     * @param $array ['1'=>2,'2'=>-2]
     */
    public function gen($selector,$selection = null,$object = null){
        if(!is_array($object) && $object!=null){
            $object = [$object];
        }else{
            return false;
        }
        $res = [];
        if(is_null($selection)){
            //生成选择器
            switch ($selector){
                //手牌
                case 1:
                    foreach ($object as $uid) {
                        $card_list = Actor::getRpc('cardList-' . $uid)->fetchList();
                        if(!empty($card_list)){
                            foreach ($card_list as $val){
                                $res[] = ['id'=>$val['card_order'],'name'=>$val['card_desc']['name']];
                            }
                        }
                    }
                    break;
                //光环
                case 2:
                    $buff_msg = $this->config->get('users.buff');
                    foreach ($object as $uid){
                        //获取buff
                        $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
                        if(!empty($game_info['buff'])){
                            foreach ($game_info['buff'] as $key=>$val){
                                $res[] = ['id'=>$key,'name'=>$buff_msg[$key]];
                            }
                        }
                    }
                    break;
            }

        }
        return $res;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}