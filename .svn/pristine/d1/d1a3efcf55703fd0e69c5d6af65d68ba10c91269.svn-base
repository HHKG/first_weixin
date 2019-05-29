<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class CouponValidate extends Validate
{
    protected $rule = [
        'E_Name' => 'require',
        'E_CouponImg'  => 'require',
        'E_Type'  => 'require|number',
        'E_IsSuperposition'  => 'number',
        'E_Money'  => 'require|number',
        'E_CanUseMoney'  => 'require|number',
        'E_Amount'  => 'require|number',
        'E_SendStartTime'  => 'require',
        'E_SendEndTime'  => 'require',
        'E_SendType'  => 'require',
        'E_LastDay'  => 'require|number',


    ];

    protected $message = [
        'E_Name.require' => '名称不能为空',
        'E_CouponImg.require'  => '图片不能为空',
        'E_Type.require'  => '类型不能为空',
        'E_SendType.require'  => '发送方式不能为空',
        'E_Money.require'  => '优惠券金额不能为空',
        'E_CanUseMoney.require'  => '可使用金额不能为空',
        'E_Amount.require'  => '优惠券总数不能为空',
        'E_SendStartTime.require'  => '发送开始时间不能为空',
        'E_SendEndTime.require'  => '发送结束时间不能为空',
        'E_LastDay.require'  => '有效时间不能为空',
        'E_Type.number'  => '联系必须是数字',
        'E_IsSuperposition.number'  => '是否可以叠加必须是数字',
        'E_CanUseMoney.number'  => '可使用金额必须是数字',
        'E_Amount.number'  => '优惠券总数必须是数字',
        'E_LastDay.number'  => '有效时间必须是数字',


    ];
//    protected $scene=[
////        'add'=>['']
//    ];

}