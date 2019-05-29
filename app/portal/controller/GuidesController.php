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

class GuidesController extends HomeBaseController
{
    public function baiduguide()
    {
        $token=$this->request->param('token')?$this->request->param('token'):'';
        if(!empty($token)){
            session('token',$token);
        }
        if (session('token')){

            $this->redirect('/index');
            exit();
        }

        // dump($this->request->routeInfo()['rule'][0]);die;

        if (cmf_is_wechat() ){
            $wxcode=session('wxcode');
            //   dump($wxcode);
            if(empty($wxcode)){
                $this->islogin();
            }
        }

        //  $openid=$this->request->param('openid')?$this->request->param('openid'):'';
//        if (empty($openid)){
//            header('location:./404.html');
//            exit();
//        }
//        dump($this->request->param());
//        dump($_REQUEST);
//        die;
        //$this->assign('openid',$openid );
        $this->assign('type',cmf_is_wechat()?3:1 );
        $this->assign('back', $this->request->param('back')?$this->request->param('back'):'');
        $this->assign('description', '注册会员');
        $this->assign('keywords', '虞心荟');
        $this->assign('title', '填写手机号-虔心荟');

//        $back=$this->request->param();
//        dump($back);die;

        return $this->fetch();
    }

}
