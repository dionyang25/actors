<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/29
 * Time: 下午7:01
 * 抽牌、弃牌相关actor
 */
namespace app\Actors;

use Server\CoreBase\Actor;

class RandomNameActor extends Actor{


    public function getName()
    {
        $first = ['赵','钱','孙','李','周','吴','郑','王','冯','陈','褚','卫','蒋','沈','韩','杨'];
        $last = ['伟','刚','勇','毅','俊','峰','强','军','平','保','东','文','辉','力','明','永','健','世','广','志','义','兴','良','海','山','仁','波','宁','贵','福'];
        return $first[array_rand($first)].$last[array_rand($last)].$last[array_rand($last)];
    }


    function registStatusHandle($key, $value)
    {
        return false;
    }
}