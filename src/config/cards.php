<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:52
 */
$config['cards'] = [
    0=>[
        'name'=>'水之矢',
        'desc'=>'打出水之箭，造成1点水属性伤害（指向，水）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585796422&di=ecb0fe795f56bc509f73aeafc27d0836&imgtype=jpg&src=http%3A%2F%2Fimg2.imgtn.bdimg.com%2Fit%2Fu%3D3590960182%2C1982815395%26fm%3D214%26gp%3D0.jpg',
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
        'name'=>'火球',
        'desc'=>'打出火球，造成1点火属性伤害（指向，火）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585838480&di=34ed5d095fb230468395d861a51304a1&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F11%2F31%2F09%2F35J58PICZRd.jpg',
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