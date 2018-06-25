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
                Actor::getRpc('cardList-'.$object)->addNewCard($effect['value'],1,0,0);
                $msg .= sprintf('%s 借助风神之力，抽 %s 张牌！',$object,$effect['value']);
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