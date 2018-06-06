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
        'desc'=>'打出水之箭，造成1点水属性伤害（资源：0/1/0，水）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585796422&di=ecb0fe795f56bc509f73aeafc27d0836&imgtype=jpg&src=http%3A%2F%2Fimg2.imgtn.bdimg.com%2Fit%2Fu%3D3590960182%2C1982815395%26fm%3D214%26gp%3D0.jpg',
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
    ],
    1=>[
        'name'=>'火球',
        'desc'=>'打出火球，造成1点火属性伤害（资源：1/0/0，火）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585838480&di=34ed5d095fb230468395d861a51304a1&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F11%2F31%2F09%2F35J58PICZRd.jpg',
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
    2=>[
        'name'=>'风之刃',
        'desc'=>'打出风刃，造成1点风属性伤害（资源：0/0/1，风）',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            3=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>1
            ]
        ],
        'is_object'=>1
    ],
    3=>[
        'name'=>'燃烧之息',
        'desc'=>'燃尽一切，造成2点火属性伤害（资源：2/0/0，火）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528284051004&di=27dd2c2aecfd5e13a4907cbd4c5dfab3&imgtype=0&src=http%3A%2F%2Fp0.ifengimg.com%2Fpmop%2F2017%2F0925%2FEABD48779A1D89F9B37CE5DE3FEB9CF64DFA2EFF_size13_w461_h261.jpeg',
        'property'=>[
            1=>2
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>2
            ]
        ],
        'is_object'=>1
    ],
    4=>[
        'name'=>'湛蓝宝典',
        'desc'=>'打出：造成1点水属性伤害（资源：0/3/0，水），覆盖：增加3点资源',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528283922356&di=db5772d4d18772d47598ce31c5c6bd63&imgtype=0&src=http%3A%2F%2Fimg4.duitang.com%2Fuploads%2Fitem%2F201407%2F02%2F20140702102150_JZhcc.jpeg',
        'property'=>[
            2=>3
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>1
            ]
        ],
        'is_object'=>1,
        'resource'=>3
    ],
    5=>[
        'name'=>'风之领域-S',
        'desc'=>'发动风神之力，己方抽2张牌（资源：0/0/2，风）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528283922356&di=db5772d4d18772d47598ce31c5c6bd63&imgtype=0&src=http%3A%2F%2Fimg4.duitang.com%2Fuploads%2Fitem%2F201407%2F02%2F20140702102150_JZhcc.jpeg',
        'property'=>[
            3=>2
        ],
        'effect'=>[
            [
                'type'=>'opcard',
                'method'=>'draw',
                'object'=>0,//表示自己
                'value'=>2
            ]
        ],
        'is_object'=>0
    ],
//    6=>[
//        'name'=>'水之颂歌',
//    ]
];
return $config;