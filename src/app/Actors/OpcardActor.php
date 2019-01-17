<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 * 抽牌、弃牌相关actor
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class OpcardActor extends Actor{


    public function dealEffect($effect,$origin_uid,$object = null){
//        if(!is_array($object) && $object!=null){
//            $object = [$object];
//        }else{
//            return false;
//        }
        $msg = '';
        switch ($effect['method']){
            //抽牌
            case 'draw':
                if(!empty($effect['selection'])){
                    $random = 0;
                    $card_id = $effect['selection'];
                    $msg .= sprintf('%s 通过抉择得到 %s 张牌！',$object,$effect['value']);
                }else{
                    $random = 1;
                    $card_id = 0;
                    $msg .= sprintf('%s 借助风神之力，抽 %s 张牌！',$object,$effect['value']);
                }
                Actor::getRpc('cardList-'.$object)->addNewCard($effect['value'],$random,0,1,$card_id);

                return ['msg'=>$msg];

                break;
            case 'discard':
                Actor::getRpc('cardList-'.$object)->discardCards($effect['value']);
                $msg .= sprintf('%s 被丢弃 %s 张牌！',$object,$effect['value']);
                return ['msg'=>$msg];
                break;
        }
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}