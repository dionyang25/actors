<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/14
 * Time: 下午2:06
 */

namespace app\Controllers;


use Server\CoreBase\ChildProxy;
use Server\CoreBase\Controller;

class BaseController extends Controller
{
    public $mysql_pool;

    public function __construct($proxy = ChildProxy::class)
    {
        parent::__construct($proxy);
        $this->mysql_pool = get_instance()->getAsynPool('mysqlPool');
    }

    protected function output($data){
        if(is_array($data)){
            $data = json_encode($data);
        }
        $this->http_output->setContentType('application/json; charset=utf-8');
        $this->http_output->end($data);
    }
}