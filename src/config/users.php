<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:52
 */
$config['users']['game_initial'] = [
    'hp'=>150,
    'card_num'=>0,
    'resource'=>[
        1=>0,2=>0,3=>0
    ]
];

$config['users']['resource'] =[
    1 =>'火',
    2 =>'水',
    3 =>'风',
    '火'=>1,
    '水'=>2,
    '风'=>3,
    'default_increase'=>2
];

$config['users']['buff'] =[
    'dmg' =>'伤害',
    'is_cover'=>'覆盖',
    'cover'=>'加速'
];

$config['users']['card'] =[
    'limit' =>10
];
return $config;