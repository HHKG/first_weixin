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

class ProjectValidate extends Validate
{
    protected $rule = [
        'E_Name' => 'require',
        'E_Type'  => 'require|number',
        'E_Status'  => 'require|number',
        'E_Address'  => 'require',
        'E_Brand'  => 'require',
        'E_Introduction'  => 'require',
        'E_HeaderImg'  => 'require',
    ];

    protected $message = [
        'E_Name.require' => '名称不能为空',
        'E_Type.require'  => '项目类型不能为空',
        'E_Status.require'  => '项目状态不能为空',
        'E_Address.require'  => '地址不能为空',
        'E_Brand.require'  => '项目品牌不能为空',
        'E_Introduction.require'  => '项目的介绍不能为空',
        'E_HeaderImg.require'  => '主图片不能为空',
        'E_Type.number'  => '项目类型必须是数字',
        'E_Status.number'  => '项目状态必须是数字',

    ];
//    protected $scene=[
////        'add'=>['']
//    ];

}