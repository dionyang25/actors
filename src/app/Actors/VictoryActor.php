<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/31
 * Time: 下午7:46
 * 胜负判定
 */
namespace app\Actors;
use Server\CoreBase\Actor;
class VictoryActor extends Actor{

    /**
     * 胜负判定
     * @param $uids
     * @return mixed false-表示无胜负 uid-表示哪方获胜
     */
    public function judge($uids,$destroy = 1){
        //目前只需判定hp为0
        $hp_res = $this->hpJudge($uids);
        if($hp_res && $destroy){
            //查找房间
            $roomActorName = Actor::getRpc('roomList')->hasRoom($uids[0]);
            $this->victoryDestroy($roomActorName);
            Actor::getRpc($roomActorName)->pubMsg(
                3001,'游戏结束！玩家'.$hp_res.'胜利！！'
            );
        }
        return $hp_res;
    }

    function registStatusHandle($key, $value)
    {
        return false;
    }

    /**
     * hp判定
     * @param $uids
     * @return bool
     */
    private function hpJudge($uids){
        if(empty($uids)){return false;}
        $opponents = [
            $uids[0]=>$uids[1],
            $uids[1]=>$uids[0]
        ];

        foreach ($uids as $uid){
            $game_info = Actor::getRpc('Player-'.$uid)->gameInfo();
            if($game_info['hp']<=0){
                return $opponents[$uid];
            }
        }
        return false;

    }

    /**
     * 胜负判定后的销毁工作
     */
    private function victoryDestroy($roomActorName){
        //销毁pid 销毁
        Actor::getRpc($roomActorName)->exitGame();
    }
}