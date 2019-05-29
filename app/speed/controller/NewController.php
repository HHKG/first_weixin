<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\speed\controller;


use think\Db;
use cmf\controller\HomeBaseController;
use app\speed\model\NewModel;


class NewController extends HomeBaseController
{
    public function page(){
        $re['msg']='请求成功！';
        $re['code']='0！';
    }
}