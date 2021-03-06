<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/6/7
 * Time: 下午3:49
 */
namespace app\Controllers;

class UserController extends BaseController {
    private $table = 'card_user';
    /**
     * 注册用户
     * @throws
     */
    public function http_register(){

        $username = $this->http_input->post('username');
        $password = $this->http_input->post('password');
        if(empty($username) || empty($password)){
           return $this->output(['error'=>-2]);
        }
        $password = md5($password);
        try{
            $res = $this->mysql_pool->dbQueryBuilder->insert($this->table)->set(
                [
                    'username'=>$username,
                    'password'=>$password
                ]
            )->query();
        }catch (\Exception $e){
            return $this->output(['error'=>-1]);
        }
        return $this->output(['error'=>0]);
    }

    /**
     * 登陆
     * @throws
     */
    public function http_login(){
        $username = $this->http_input->post('username');
        $password = $this->http_input->post('password');
        if(empty($username) || empty($password)){
            return $this->output(['error'=>-2]);
        }
        $password = md5($password);
        try{
            $res = $this->mysql_pool->dbQueryBuilder->select('*')->from($this->table)
                ->where('username',$username)
                ->where('password',$password)
            ->limit(1)->query()->row();
        }catch (\Exception $e){
            echo $e->getMessage();
            return $this->output(['error'=>-1]);
        }
        if(empty($res)){
            return $this->output(['error'=>-10]);
        }
        //写session
        $_SESSION['user'] = $username;
        return $this->output(['error'=>0]);
    }

    /**
     * 输出规则
     */
    public function http_rules(){
        return $this->output(['data'=>$this->config->get('games.rules')]);
    }
    /**
     * 登出
     * @throws
     */
    public function http_logout(){
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }
        return $this->output(['error'=>0]);
    }
}