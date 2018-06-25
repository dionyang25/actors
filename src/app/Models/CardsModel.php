<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/25
 * Time: 下午2:21
 */

namespace app\Models;


use Server\CoreBase\Model;

class CardsModel extends Model
{
    public function loadCards(){
        //测试
//        return $this->config->get('cards');
        //正式
        $info = $this->redis_pool->getCoroutine()->get('cards_info');
        if(empty($info)){
            $info = [];
        }
        return json_decode($info,true);
    }
}