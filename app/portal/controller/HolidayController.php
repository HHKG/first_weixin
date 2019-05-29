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
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use app\portal\service\PostService;
use app\portal\model\PortalPostModel;
use think\Db;

class HolidayController extends HomeBaseController
{
   public function index(){
    $this->assign('holiday', '1');
       $this->assign('description', '虔心荟');
       $this->assign('keywords', '虔心荟');
       $this->assign('title', '度假地-虔心荟');
       return $this->fetch();
   }
   public function scenery(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '生态风光-虔心荟');
    return $this->fetch();
}
public function detail(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '酒店详情-虔心荟');
    return $this->fetch();
}
public function free(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}
public function detail_two(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}
public function detail_three(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}
public function detail_four(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}
public function detail_five(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}
public function detail_six(){
    $this->assign('description', '虔心荟');
    $this->assign('keywords', '虔心荟');
    $this->assign('title', '参与分享计划免费度假攻略-虔心荟');
    return $this->fetch();
}

}
