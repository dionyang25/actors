<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/22
 * Time: 下午6:36
 */

namespace app\Controllers;


class CardsController extends BaseController
{
    private $table = 'card_cards';
    /**
     * 导入卡
     */
    public function http_import(){
//        $cards = $this->config->get('cards');
//        foreach ($cards as $key1=>$vo){
//            $vo['effect'] = json_encode($vo['effect']);
//            $max_val = 0;
//            $max_key = 0;
//            foreach ($vo['property'] as $key=>$val){
//                $vo['property_'.$key] = $val;
//                if($val > $max_val){
//                    $max_key = $key;
//                }
//            }
//            $vo['property_main'] = $max_key;
//            $vo['description'] = $vo['desc'];
//            $vo['serial_no'] = $key1;
//            unset($vo['property'],$vo['desc']);
//            $this->mysql_pool->dbQueryBuilder->insert($this->table)->set(
//                $vo
//            )->query();
//        }
//        return true;
    }

    /**
     * 导出卡到yac
     */
    public function export(){

    }
}