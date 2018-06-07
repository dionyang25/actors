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