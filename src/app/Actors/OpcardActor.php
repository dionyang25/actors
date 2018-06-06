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
        switch ($effect['method']){
            //抽牌
            case 'draw':
                if(isset($effect['object']) && $effect['object'] == 1){

                }else{
                    Actor::getRpc('cardList-'.$origin_uid)->addNewCard($effect['value']);
                    //自己减少一张手牌计数
                    Actor::getRpc('cardList-'.$origin_uid)->addCardNum($origin_uid,-1);
                    return true;
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