<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/7
 * Time: 下午3:49
 */
namespace app\Controllers;
use Server\CoreBase\Controller;

class UserController extends BaseController {
    /**
     * 注册
     */
    public function http_register(){
        $username = $this->http_input->post('username');
        $password = $this->http_input->post('password');

    }
}