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
        $cards = $this->config->get('cards');
        foreach ($cards as $key1=>$vo){
            $vo['effect'] = json_encode($vo['effect']);
            $max_val = 0;
            $max_key = 0;
            foreach ($vo['property'] as $key=>$val){
                $vo['property_'.$key] = $val;
                if($val > $max_val){
                    $max_key = $key;
                }
            }
            $vo['property_main'] = $max_key;
            $vo['description'] = $vo['desc'];
            $vo['serial_no'] = $key1;
            unset($vo['property'],$vo['desc']);
            try{
                $this->mysql_pool->dbQueryBuilder->insert($this->table)->set(
                    $vo
                )->query();
            }catch(\Exception $e){
                echo $e->getMessage();
            }

        }
        return true;
    }

    /**
     * 导出卡到yac
     */
    public function export(){
        //取list
        $list = $this->mysql_pool->dbQueryBuilder->select('*')->from($this->table)->query();
        foreach ($list as &$vo){
            $vo['effect'] = json_decode($vo['effect']);
            $vo['desc'] = $vo['description'];
            $vo['property'][1] = $vo['property_1'];
            $vo['property'][2] = $vo['property_2'];
            $vo['property'][3] = $vo['property_3'];
            unset($vo['property_1'],$vo['property_2'],$vo['property_3'],$vo['description']);
        }
        //导入到yac

        
    }
}