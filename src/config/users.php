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
        1=>1,2=>1,3=>1
    ]
];

$config['users']['resource'] =[
    1 =>'火',
    2 =>'水',
    3 =>'风',
    '火'=>1,
    '水'=>2,
    '风'=>3,
    'default_increase'=>2,
//    'limit'=>10
];

$config['users']['buff'] =[
    'dmg' =>'伤害',
    'vulnerability'=>'寒伤',
    'is_cover'=>'覆盖',
    'cover'=>'加速',
    'duration_dmg'=>'持续伤害',
    'duration_recover'=>'持续回复',
    'duration_draw_card'=>'持续抽牌',
    'duration_vulnerability'=>'持续寒伤',
    'duration_reducer'=>'持续减伤',
    'restrict_draw'=>'冻结',
    'reducer'=>'减伤',
];

//手牌上限
$config['users']['card'] =[
    'limit' =>8
];

$config['users']['level_up'] = [
    16=>40,
    17=>38,
    18=>42,
    38=>39,
    40=>41,
    42=>43,
    21=>54,
    54=>55,
    20=>56,
    56=>57,
    19=>58,
    58=>59,
    65=>66,
    66=>67,
    72=>73,
    73=>74,
    85=>86,
    86=>87,
    93=>94,
    94=>95,
];
return $config;