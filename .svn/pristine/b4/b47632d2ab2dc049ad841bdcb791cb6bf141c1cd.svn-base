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

class ProjectplanValidate extends Validate
{
    protected $rule = [
        'E_Title' => 'require',
        'E_ProjectCode' => 'require',
        'E_Status'  => 'require|number',
        'E_Cycle'  => 'require|number',
        'E_money'  => 'require|number',
        'E_sum'  => 'require|number',
        'E_max'  => 'require|number',
        'E_Introduction'  => 'require',
    ];

    protected $message = [
        'E_Title.require' => '方案名称不能为空',
        'E_ProjectCode.require' => '项目编号不能为空',
        'E_Status.require' => '状态不能为空',
        'E_Cycle.require'  => '周期不能为空',
        'E_money.require'  => '认购金额不能为空',
        'E_sum.require'  => '总数不能为空',
        'E_max.require'  => '限购份数不能为空',
        'E_Introduction.require'  => '方案说明不能为空',
        'E_Status.number'  => '状态必须是数字',
        'E_Cycle.number'  => '周期必须是数字',
        'E_money.number'  => '认购金额必须是数字',
        'E_sum.number'  => '总数必须是数字',
        'E_max.number'  => '限购份数必须是数字',
    ];
//    protected $scene=[
////        'add'=>['']
//    ];

}