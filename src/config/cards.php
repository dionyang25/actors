<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:52
 */
$config['cards'] = [
    0=>[
        'name'=>'water arrow',
        'desc'=>'打出水之箭，造成1点水属性伤害（指向，水）',
        'pic'=>'',
        'property'=>[
            1=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>1
            ]
        ],
        'is_object'=>1
    ],
    1=>[
        'name'=>'fire ball',
        'desc'=>'打出火球，造成1点火属性伤害（指向，火）',
        'pic'=>'',
        'property'=>[
            2=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>1
            ]
        ],
        'is_object'=>1
    ]
];
return $config;