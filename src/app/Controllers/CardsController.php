<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/22
 * Time: 下午6:36
 */

namespace app\Controllers;


use app\Models\CardsModel;

class CardsController extends BaseController
{
    private $table = 'card_cards';
    /**
     * 导入卡
     * @throws
     */
    public function http_import(){
        $cards = $this->config->get('cards');
        foreach ($cards as $vo){
            $vo['effect'] = json_encode($vo['effect']);
            $max_val = 0;
            $max_key = 0;
            foreach ($vo['property'] as $key=>$val){
                $vo['property_'.$key] = $val;
                if($val > $max_val){
                    $max_val = $val;
                    $max_key = $key;
                }
            }
            $vo['property_main'] = $max_key;
            $vo['description'] = $vo['desc'];
            unset($vo['property'],$vo['desc']);
            try{
                $this->mysql_pool->dbQueryBuilder->insert($this->table)->set(
                    $vo
                )->query();
            }catch(\Exception $e){
                echo $e->getMessage();
                continue;
            }

        }
        return true;
    }

    /**
     * 导出卡到yac
     * @throws
     */
    public function http_export(){
        //取全卡list
        $list = $this->mysql_pool->dbQueryBuilder->select('*')->from($this->table)->query();
        $list = $list['result'];
        //存全卡
        $all = [];
        //只存可获得的卡
        $avail = [];
        $property_main = [1=>'🔥',2=>'💧',3=>'☁️'];
        foreach ($list as $vo){
            $vo['effect'] = json_decode($vo['effect']);
            $vo['desc'] = $vo['description'];
            $vo['property'][1] = $vo['property_1'];
            $vo['property'][2] = $vo['property_2'];
            $vo['property'][3] = $vo['property_3'];
            $vo['is_object'] = (int)$vo['is_object'];
            $vo['selector'] = (int)$vo['selector'];
            $vo['name'] = $property_main[$vo['property_main']].$vo['name'];
            unset($vo['property_1'],$vo['property_2'],$vo['property_3'],
                $vo['description']);
            $all[$vo['id']] = $vo;
            if((int)$vo['card_status'] == 2){
                $avail[$vo['id']] = $vo;
            }
        }
        //导入到redis
        $this->redis_pool->getCoroutine()->set('cards_info',json_encode($all));
        $this->redis_pool->getCoroutine()->set('cards_avail',json_encode($avail));
        $this->output($avail);
    }

    /**
     * 读取全卡
     */
    public function http_loadCards(){
        //取list
        $ret = $this->loader->model(CardsModel::class,$this)->loadCards();
        $this->output($ret);
    }
}