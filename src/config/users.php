<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:52
 */
$config['users']['game_initial'] = [
    'hp'=>15,
    'card_num'=>0,
    'resource'=>[
        1=>0,2=>0,3=>0
    ]
];

$config['users']['resource'] =[
    1 =>'fire',
    2 =>'water',
    3 =>'wind',
    'fire'=>1,
    'water'=>2,
    'wind'=>3
];
return $config;