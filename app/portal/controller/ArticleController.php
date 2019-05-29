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

class ArticleController extends HomeBaseController
{
   public function index(){
       $type=$this->request->param('type');

       $mobile=$this->request->param('mobile');
       $code=$this->request->param('mcode');
       $openid=$this->request->param('openid');
       $backurl=$this->request->param('backurl');

       $this->assign('description', '注册登录');
       $this->assign('keywords', '虔心荟');
       $this->assign('title', '会员登录-虔心荟');
       $this->assign('type', $type);
       $this->assign('mobile', $mobile);
       $this->assign('mcode', $code);
       $this->assign('openid', $openid);
       $this->assign('backurl', $backurl);
       return $this->fetch();
   }

}
