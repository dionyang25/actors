<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/26
 * Time: 下午5:50
 */

namespace app\Actors;


use Server\CoreBase\Actor;

class LevelActor extends Actor
{
    /**
     * 卡牌升级！效果
     * @param $effect
     * @param $origin_uid
     * @param null $object
     * @return
     *  [
            'type'=>'level',
            'method'=>'up',
            'card'=>'all',
            'object'=>13,//表示自己
            'value'=>1
        ]
     *
     */
    public function dealEffect($effect,$origin_uid,$object = null){
        $msg = '';
        if(empty($effect['value'])){
            $effect['value'] = 1;
        }
        //升级次数
        for ($i = 1;$i<=$effect['value'];$i++){
            switch($effect['method']){
                case 'up':
                    if(isset($effect['selection'])){
                        //升级单卡
                        Actor::getRpc('cardList-'.$origin_uid)->upgrade($effect['selection']);
                    }else{
                        //升级全部卡牌的逻辑
                        Actor::getRpc('cardList-'.$origin_uid)->upgrade();
                    }
                    $msg .= sprintf('%s 手中卡牌的等级提升了！',$origin_uid);
                    break;
            }
        }

        return ['msg'=>$msg];
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}