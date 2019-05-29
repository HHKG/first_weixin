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

use app\portal\model\NewModel;
use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;

class ProjectController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
//        dump(session('token'));die;
    }

    public function index()
    {
        $project=$this->request->param('project');
//        dump($project);die;
        if (empty($project)){
            $this->redirect('/index');
        }
        $incode=$this->request->param('incode');
        if (!empty($incode)){
            session('incode',$incode);
        }
        $this->assign('project',$project);
        $this->assign('description', '分享计划详情');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '分享计划详情-虔心荟');
        return $this->fetch();         
    }
    public function detail(){
        $project=$this->request->param('project');
        if (empty($project)){
            $this->redirect('/index');
        }
        $this->assign('project',$project);
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '分享计划详情-虔心荟');
        return $this->fetch();
    }
    public function coupons(){
        // $this->assign('project',$project);
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '邀请好友免费领￥500元住房券-虔心荟');
        return $this->fetch();
    }
    public function plans(){
        $this->islogin();
        $project=$this->request->param('project');
//        dump($code);die;
//        dump($code);die;
        if (empty($project)){
            $this->redirect('/index');
        }
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '返利方案-虔心荟');
        $this->assign('project',$project);
        return $this->fetch();
    }
}
