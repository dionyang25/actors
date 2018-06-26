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
    /**
     * @param int $avail 只取可用的卡
     * @return mixed
     */
    public function loadCards($avail = 0){
        //测试
//        return $this->config->get('cards');
        //正式
        if($avail){
            $info = $this->redis_pool->getCoroutine()->get('cards_avail');
        }else{
            $info = $this->redis_pool->getCoroutine()->get('cards_info');
        }
        if(empty($info)){
            $info = [];
        }
        return json_decode($info,true);
    }
}