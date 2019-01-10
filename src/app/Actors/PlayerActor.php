<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/17
 * Time: 15:34
 */
namespace app\Actors;

use Server\CoreBase\Actor;
use Server\CoreBase\ChildProxy;

class PlayerActor extends Actor{
    /**
     * 储存用户信息
     * @param $user_info
     * @throws
     */
    public function initData($user_info){
        $this->saveContext['user_info'] = $user_info;
        //订阅用户消息
        get_instance()->addSub('Player/'.$this->name,$user_info['uid']);
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

    public function gameInfo(){
        return $this->saveContext->getData()['game_info'];
    }

    /**
     * @param int $init 1-为初始化数据
     * @param array $game_info
     * @return array
     */
    public function addGameInfo($init = 0,$game_info=[]){
        if($init){
            $game_info = $this->config->get('users.game_initial');
        }
        $this->saveContext->getData()['game_info'] = $game_info;
        $this->saveContext->save();
        //发布游戏信息信息

//        get_instance()->pub('Room/'.$this->name,$data);
        return $game_info;
    }

    public function changeGameInfo($game_info=[]){
        $this->saveContext->getData()['game_info'] = array_merge($this->saveContext->getData()['game_info'],$game_info);
        $this->saveContext->save();
        //发布信息变更
        return $this->saveContext->getData()['game_info'];
    }

    /**
     * 发布用户信息
     * @throws
     */
    public function pubMsg($type,$msg,$params = []){
        $data = [
            'type'=>(string)$type,
            'msg'=>$msg,
            'params'=>$params
        ];
        get_instance()->pub('Player/'.$this->name,$data);
    }

    /**
     * 回合结束的buff结算
     */
    public function calcBuff(){
        if(!empty($this->saveContext->getData()['game_info']['buff'])){
            foreach ($this->saveContext->getData()['game_info']['buff'] as $key=>&$val){
                switch ($key){
                    //盖卡buff
                    default:
                        --$val[0];
                        if($val[0]<=0){
                            unset($this->saveContext->getData()['game_info']['buff'][$key]);
                        }
                        break;
                }

            }
        }
    }

    /**
     * 卡牌资源校验/扣除
     * is_done =1 扣除资源
     */
    public function checkCardResource($card_desc,$is_done=0){
        $temp = [];
        foreach ($card_desc['property'] as $key=>$val){
            $temp[$key] = $this->saveContext->getData()['game_info']['resource'][$key] - $val;
            if ($temp[$key]<0){
                return false;
            }
        }
        if($is_done){
            $this->saveContext->getData()['game_info']['resource'] =
                $temp + $this->saveContext->getData()['game_info']['resource'];
        }
        return true;
    }

    /**
     * 获取buff的数值
     */
    public function getBuffInfo($buff_type){
        $game_info = $this->gameInfo();
        if(isset($game_info['buff'][$buff_type])){
            return $game_info['buff'][$buff_type][1];
        }
        return false;
    }
}