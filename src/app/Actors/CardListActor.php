<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:40
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class CardListActor extends Actor{

    //添加一张卡
    public function addNewCard($num = 1,$random = 1){
        $card_list = $this->config->get('cards');
        //随机取卡
        if($random){
            for($i=1;$i<=$num;$i++){
                $key = array_rand($card_list);
                $this->saveContext->getData()['list'][] = $key;
                $this->saveContext->save();
            }
        }
    }

    //打出一张卡


    //返回所有卡的属性
    public function fetchList(){
        $list = $this->saveContext->getData()['list'];
        $card_list = $this->config->get('cards');
        $ret = [];
        foreach ($list as $vo){
           $ret[] = [
               'card_id'=>$vo,
               'card_desc'=>$card_list[$vo]
           ];
        }
        return $ret;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }
}