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
                if(isset($effect['object']) && $effect['object'] == 12){
                    //对手
                }else{
                    Actor::getRpc('cardList-'.$origin_uid)->addNewCard($effect['value'],1,0,0);
                    $msg .= sprintf('%s 借助风神之力，抽 %s 张牌！',$origin_uid,$effect['value']);
                    return ['msg'=>$msg];
                }

                break;
            case 'discard':
                break;
        }
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}