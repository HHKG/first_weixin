<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
return [
    'Order/index' => [
        'name' => '加入会籍',
        'vars' => [
            'pcode' => [
                'pattern' => '\w+',
                'require' => true
            ],
        ],
        'simple' => true
    ],
    'Order/paysuccess' => [
        'name' => '支付成功',
        'vars' => [
            'sn' => [
                'pattern' => '\w+',
                'require' => true
            ],
        ],
        'simple' => true
    ],
    'Order/myorder' => [
        'name' => '订单列表',
        'vars' => [],
        'simple' => true
    ],
    'Project/detail' => [
        'name' => '项目详情',
        'vars' => [
            'pcode' => [
                'pattern' => '\w+',
                'require' => true
            ],
        ],
        'simple' => true
    ],
    'Index/index' => [
        'name' => '前台首页',
        'vars' => [],
        'simple' => true
    ],

    'Login/index' => [
        'name' => '前台登录',
        'vars' => [],
        'simple' => true
    ],
    'New/index' => [
        'name' => '新闻',
        'vars' => [
        ],
        'simple' => true
    ],
    'New/n_show' => [
        'name' => '新闻-详情',
        'vars' => [
            'id' => [
                'pattern' => '\d+',
                'require' => true
            ]
        ],
        'simple' => true
    ],

    'Member/index' => [
        'name' => '会员-首页',
        'vars' => [
        ],
        'simple' => true
    ],
];
