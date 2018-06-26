<?php
/**
 * Created by PhpStorm.
 * User: yanghaonan
 * Date: 2018/5/22
 * Time: 14:52
 * 12-对手 13-自己
 */
$config['cards'] = [
//    0=>[
//        'name'=>'水之矢',
//        'desc'=>'打出水之箭，造成12点水属性伤害（资源：0/1/0，水）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585796422&di=ecb0fe795f56bc509f73aeafc27d0836&imgtype=jpg&src=http%3A%2F%2Fimg2.imgtn.bdimg.com%2Fit%2Fu%3D3590960182%2C1982815395%26fm%3D214%26gp%3D0.jpg',
//        'property'=>[
//            2=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>12
//            ]
//        ],
//        'is_object'=>1
//    ],
//    1=>[
//        'name'=>'火球',
//        'desc'=>'打出火球，造成12点火属性伤害（资源：1/0/0，火）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585838480&di=34ed5d095fb230468395d861a51304a1&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F11%2F31%2F09%2F35J58PICZRd.jpg',
//        'property'=>[
//            1=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>12
//            ]
//        ],
//        'is_object'=>1
//    ],
//    2=>[
//        'name'=>'风之刃',
//        'desc'=>'打出风刃，造成12点风属性伤害（资源：0/0/1，风）',
//        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
//        'property'=>[
//            3=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>12
//            ]
//        ],
//        'is_object'=>1
//    ],
//    3=>[
//        'name'=>'燃烧之息',
//        'desc'=>'燃尽一切，造成23点火属性伤害（资源：2/0/0，火）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528284051004&di=27dd2c2aecfd5e13a4907cbd4c5dfab3&imgtype=0&src=http%3A%2F%2Fp0.ifengimg.com%2Fpmop%2F2017%2F0925%2FEABD48779A1D89F9B37CE5DE3FEB9CF64DFA2EFF_size13_w461_h261.jpeg',
//        'property'=>[
//            1=>2
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>23
//            ]
//        ],
//        'is_object'=>1
//    ],
//    4=>[
//        'name'=>'湛蓝宝典',
//        'desc'=>'打出：回复20点生命值（资源：0/2/0，水），覆盖：增加3点资源',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528283922356&di=db5772d4d18772d47598ce31c5c6bd63&imgtype=0&src=http%3A%2F%2Fimg4.duitang.com%2Fuploads%2Fitem%2F201407%2F02%2F20140702102150_JZhcc.jpeg',
//        'property'=>[
//            2=>2
//        ],
//        'effect'=>[
//            [
//                'type'=>'recover',
//                'value'=>20
//            ]
//        ],
//        'is_object'=>1,
//        'resource'=>3
//    ],
//    5=>[
//        'name'=>'风之领域-S',
//        'desc'=>'发动风神之力，己方抽2张牌（资源：0/0/2，风）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528370455450&di=5e1d716f902f33b033a15aad82128205&imgtype=0&src=http%3A%2F%2Fa.hiphotos.baidu.com%2Fbaike%2Fc0%253Dbaike60%252C5%252C5%252C60%252C20%253Bt%253Dgif%2Fsign%3D7c898109ccbf6c81e33a24badd57da50%2Ff603918fa0ec08fab13676c659ee3d6d55fbda99.jpg',
//        'property'=>[
//            3=>2
//        ],
//        'effect'=>[
//            [
//                'type'=>'opcard',
//                'method'=>'draw',
//                'object'=>13,//表示自己
//                'value'=>2
//            ]
//        ],
//        'is_object'=>0
//    ],
//    6=>[
//        'name'=>'潮漩之歌',
//        'desc'=>'对对手造成23点水属性伤害，自己回复30点生命（资源：0/4/1，水）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528967212&di=255fa3f82130cb69a967180d7f5a7df5&imgtype=jpg&er=1&src=http%3A%2F%2Fimgsrc.baidu.com%2Fimgad%2Fpic%2Fitem%2F80cb39dbb6fd5266c10cc92fa018972bd40736e1.jpg',
//        'property'=>[
//            2=>4,3=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>23,
//                'object'=>12//对手
//            ],
//            [
//                'type'=>'recover',
//                'value'=>30,
//                'object'=>13
//            ],
//        ],
//        'is_object'=>0
//    ],
//    7=>[
//        'name'=>'炎蝶之舞',
//        'desc'=>'召唤炎蝶，造成50点火属性伤害（资源：5/0/0，火）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528372383991&di=5859bf7f20df58cd3c3e4de07308abb1&imgtype=0&src=http%3A%2F%2F58pic.ooopic.com%2F58pic%2F21%2F57%2F45%2F07t58PIC7R6.jpg',
//        'property'=>[
//            1=>5
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>50
//            ]
//        ],
//        'is_object'=>1,
//    ],
//    8=>[
//        'name'=>'风之精灵',
//        'desc'=>'自己的4回合内造成的任意伤害+10（资源：1/1/3，风，buff）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528373507016&di=2af9cf04be8f901e2fd010b42e2b5cfc&imgtype=0&src=http%3A%2F%2Fimg1.comic.zongheng.com%2Fcomic%2Fimage%2F2009%2F2%2Fs164849750%2F500_500%2F20090307091428079196.jpg',
//        'property'=>[
//            1=>1,2=>1,3=>3
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'dmg',
//                'value'=>10,
//                'object'=>13,
//                'turns'=>4
//            ]
//        ],
//        'is_object'=>0,
//    ],
//    9=>[
//        'name'=>'风之镰',
//        'desc'=>'造成35点风属性伤害，自身抽一张卡（资源：0/0/4，风）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528439878986&di=56aaf79a0eeed222e2ccb1477c73f352&imgtype=0&src=http%3A%2F%2Fimg01.3dmgame.com%2Fuploads%2Fallimg%2F140220%2F235_140220210407_1_lit.jpg',
//        'property'=>[
//            3=>4
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>35
//            ],
//            [
//                'type'=>'opcard',
//                'method'=>'draw',
//                'object'=>13,//表示自己
//                'value'=>1
//            ]
//        ],
//        'is_object'=>1,
//    ],
//    10=>[
//        'name'=>'水之精灵',
//        'desc'=>'6回合内，自身获得的所有资源+1（资源：0/2/0，水，buff）',
//        'pic'=>'https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=3227131090,1779620457&fm=27&gp=0.jpg',
//        'property'=>[
//            2=>2
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'cover',
//                'value'=>1,
//                'object'=>13,
//                'turns'=>6
//            ]
//        ],
//        'is_object'=>0,
//    ],
//    11=>[
//        'name'=>'炎之精灵',
//        'desc'=>'5回合内，对手获得的所有资源-1（资源：2/0/1，火，buff），覆盖：增加3点资源',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528439925360&di=26007def9adf842cbe50376baf401143&imgtype=0&src=http%3A%2F%2Fa.hiphotos.baidu.com%2Fbaike%2Fw%253D268%253Bg%253D0%2Fsign%3De19bb3a5c9177f3e1034fb0b48f45cfa%2Fd6ca7bcb0a46f21f8e5a7f40f6246b600c33aeb2.jpg',
//        'property'=>[
//            1=>2,2=>0,3=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'cover',
//                'value'=>-1,
//                'object'=>12,
//                'turns'=>5
//            ]
//        ],
//        'resource'=>3,
//        'is_object'=>0,
//    ],
//    12=>[
//        'name'=>'火灵术',
//        'desc'=>'3回合内，每回合在自己的结束阶段，对对手造成11点火属性伤害（资源：3/0/0，火，buff）',
//        'pic'=>'https://img.moegirl.org/common/thumb/c/c1/Spiritual_Fire_Art_-_Kurenai.jpg/500px-Spiritual_Fire_Art_-_Kurenai.jpg',
//        'property'=>[
//            1=>3,
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'duration_dmg',
//                'value'=>'',
//                'turns'=>3,
//                'object'=>13,
//                'duration'=>[
//                    [
//                        'type'=>'dmg',
//                        'value'=>11,
//                        'object'=>12
//                    ]
//                ]
//            ]
//        ],
//        'is_object'=>0,
//    ],
//    13=>[
//        'name'=>'水灵术',
//        'desc'=>'3回合内，每回合在目标的结束阶段，回复目标10点生命值（资源：0/3/0，水，buff），覆盖：增加3点资源',
//        'pic'=>'https://img.moegirl.org/common/thumb/7/78/Spiritual_Water_Art_-_Aoi.jpg/500px-Spiritual_Water_Art_-_Aoi.jpg',
//        'property'=>[
//            2=>3,
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'duration_recover',
//                'value'=>'',
//                'turns'=>3,
//                'duration'=>[
//                    [
//                        'type'=>'recover',
//                        'value'=>10,
//                        'object'=>13
//                    ]
//                ]
//            ]
//        ],
//        'resource'=>3,
//        'is_object'=>1,
//    ],
//    14=>[
//        'name'=>'风灵术',
//        'desc'=>'3回合内，每回合在目标的结束阶段，获得一张卡牌，并回复2点生命值（资源：0/1/2，风，buff）',
//        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1529672996754&di=706c9135b3bb3494c38f3f09687105fe&imgtype=0&src=http%3A%2F%2Fp4.qhmsg.com%2Ft01981e3cb8fbb45ee4.jpg',
//        'property'=>[
//            2=>1,3=>2
//        ],
//        'effect'=>[
//            [
//                'type'=>'buff',
//                'section'=>'duration_draw_card',
//                'value'=>'',
//                'turns'=>3,
//                'duration'=>[
//                    [
//                        'type'=>'opcard',
//                        'method'=>'draw',
//                        'object'=>13,//表示自己
//                        'value'=>1
//                    ],
//                    [
//                        'type'=>'recover',
//                        'object'=>13,//表示自己
//                        'value'=>2
//                    ]
//                ]
//            ]
//        ],
//        'resource'=>3,
//        'is_object'=>1,
//    ],
//    15=>[
//        'name'=>'怒涛之舞踏',
//        'desc'=>'对对手造成40点伤害，对手3回合之内无法覆盖卡牌（资源：1/6/1，水，buff）',
//        'pic'=>'https://img.moegirl.org/common/3/3c/Waterw.jpg',
//        'property'=>[
//            1=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>40,
//                'object'=>12//对手
//            ],
//            [
//                'type'=>'buff',
//                'section'=>'is_cover',
//                'value'=>'',
//                'turns'=>3,
//                'object'=>12
//            ]
//        ],
//        'is_object'=>0,
//    ],
//    16=>[
//        'name'=>'龙卷之赞歌',
//        'desc'=>'对对手造成40点伤害，对手随机丢弃两张卡牌（资源：1/1/6，风）',
//        'pic'=>'https://img.moegirl.org/common/thumb/c/cd/Twister.jpg/500px-Twister.jpg',
//        'property'=>[
//            1=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>40,
//                'object'=>12//对手
//            ],
//            [
//                'type'=>'opcard',
//                'method'=>'discard',
//                'object'=>12,
//                'value'=>2
//            ]
//        ],
//        'is_object'=>0,
//    ],
//    17=>[
//        'name'=>'红莲之颂咏',
//        'desc'=>'对对手造成45点伤害，对手3回合之内无法打出卡牌（资源：6/1/1，火，buff）',
//        'pic'=>'https://img.moegirl.org/common/thumb/6/60/Crimson_Fire.jpg/500px-Crimson_Fire.jpg',
//        'property'=>[
//            1=>1
//        ],
//        'effect'=>[
//            [
//                'type'=>'dmg',
//                'value'=>45,
//                'object'=>12
//            ],
//            [
//                'type'=>'buff',
//                'section'=>'restrict_draw',
//                'value'=>'',
//                'turns'=>3,
//                'object'=>12
//            ]
//        ],
//        'is_object'=>0,
//    ]
    18=>[
        'name'=>'火球-Lv2',
        'desc'=>'打出火球，造成22点火属性伤害，（资源：1/0/0，火）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585838480&di=34ed5d095fb230468395d861a51304a1&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F11%2F31%2F09%2F35J58PICZRd.jpg',
        'property'=>[
            1=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>22,
            ]
        ],
        'is_object'=>1,
        'card_status'=>0
    ],
    19=>[
        'name'=>'火球-Lv3',
        'desc'=>'打出火球，造成22点火属性伤害，并且3回合内，每回合在自己的结束阶段，对对手造成4点火属性伤害（资源：1/0/0，火, buff）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585838480&di=34ed5d095fb230468395d861a51304a1&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F11%2F31%2F09%2F35J58PICZRd.jpg',
        'property'=>[
            1=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>22,
            ],
            [
                'type'=>'buff',
                'section'=>'duration_dmg',
                'value'=>'',
                'turns'=>3,
                'object'=>13,
                'duration'=>[
                    [
                        'type'=>'dmg',
                        'value'=>4,
                        'object'=>12
                    ]
                ]
            ]
        ],
        'is_object'=>1,
        'card_status'=>0
    ],
    20=>[
        'name'=>'水之矢-Lv2',
        'desc'=>'打出水之箭，对对手造成12点水属性伤害，回复自身12点生命值（资源：0/1/0，水）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585796422&di=ecb0fe795f56bc509f73aeafc27d0836&imgtype=jpg&src=http%3A%2F%2Fimg2.imgtn.bdimg.com%2Fit%2Fu%3D3590960182%2C1982815395%26fm%3D214%26gp%3D0.jpg',
        'property'=>[
            2=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>12,
                'object'=>12
            ],
            [
                'type'=>'recover',
                'value'=>12,
                'object'=>13
            ]
        ],
        'is_object'=>0,
        'card_status'=>0
    ],
    21=>[
        'name'=>'水之矢-Lv3',
        'desc'=>'打出水之箭，对对手造成14点水属性伤害，并且3回合内，每回合在自己的结束阶段，回复自身8点生命值（资源：0/1/0，水，buff）',
        'pic'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527585796422&di=ecb0fe795f56bc509f73aeafc27d0836&imgtype=jpg&src=http%3A%2F%2Fimg2.imgtn.bdimg.com%2Fit%2Fu%3D3590960182%2C1982815395%26fm%3D214%26gp%3D0.jpg',
        'property'=>[
            2=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>14,
                'object'=>12
            ],
            [
                'type'=>'buff',
                'section'=>'duration_recover',
                'value'=>'',
                'turns'=>3,
                'duration'=>[
                    [
                        'type'=>'recover',
                        'value'=>8,
                        'object'=>13
                    ]
                ]
            ]

        ],
        'is_object'=>0,
        'card_status'=>0
    ],
    22=>[
        'name'=>'风之刃-Lv2',
        'desc'=>'打出风刃，造成10点风属性伤害，自身抽1张卡（资源：0/0/1，风）',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            3=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>10
            ],
            [
                'type'=>'opcard',
                'method'=>'draw',
                'object'=>13,//表示自己
                'value'=>1
            ]
        ],
        'is_object'=>1,
        'card_status'=>0
    ],
    23=>[
        'name'=>'风之刃-Lv3',
        'desc'=>'打出风刃，造成6点风属性伤害，自身抽1张卡，对手随机弃1张卡（资源：0/0/1，风）',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            3=>1
        ],
        'effect'=>[
            [
                'type'=>'dmg',
                'value'=>6
            ],
            [
                'type'=>'opcard',
                'method'=>'draw',
                'object'=>13,//表示自己
                'value'=>1
            ],
            [
                'type'=>'opcard',
                'method'=>'discard',
                'object'=>12,
                'value'=>1
            ]
        ],
        'is_object'=>1,
        'card_status'=>0
    ],
    24=>[
        'name'=>'等级提升-绿',
        'desc'=>'使手牌中所有带-Lv的手牌，等级提升1级（资源：0/0/2，风），覆盖：增加3点资源',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            3=>2
        ],
        'effect'=>[
            [
                'type'=>'level',
                'method'=>'up',
                'card'=>'all',
                'object'=>13,//表示自己
                'value'=>1
            ]
        ],
        'is_object'=>1,
        'resource'=>3
    ],
    25=>[
        'name'=>'等级提升-红',
        'desc'=>'使手牌中所有带-Lv的手牌，等级提升1级（资源：2/0/0，火)，覆盖：增加3点资源',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            1=>2
        ],
        'effect'=>[

            [
                'type'=>'level',
                'method'=>'up',
                'card'=>'all',
                'object'=>13,//表示自己
                'value'=>1
            ]
        ],
        'is_object'=>0,
        'resource'=>3
    ],
    26=>[
        'name'=>'等级提升-蓝',
        'desc'=>'使手牌中所有带-Lv的手牌，等级提升1级（资源：0/2/0，水），覆盖：增加3点资源',
        'pic'=>'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1528272881&di=bb0f73ae3d6ba21b0a1cd808c23edbb4&src=http://images.17173.com/2012/news/2012/06/28/gxy0628cm02s.jpg',
        'property'=>[
            2=>2
        ],
        'effect'=>[
            [
                'type'=>'level',
                'method'=>'up',
                'card'=>'all',
                'object'=>13,//表示自己
                'value'=>1
            ]
        ],
        'is_object'=>0,
        'resource'=>3
    ],
];
return $config;