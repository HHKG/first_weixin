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
use cmf\lib\wx\Wx;
use think\Db;

class AdController extends HomeBaseController
{
    public function _initialize()
    {

        parent::_initialize();
    }
    public function guide()
    {
        $token=$this->request->param('token')?$this->request->param('token'):'';
        if(!empty($token)){
            session('token',$token);
        }
//         if (session('token')){
//
//              $this->redirect('/index');
//              exit();
//         }

        // dump($this->request->routeInfo()['rule'][0]);die;

        if (cmf_is_wechat() ){
            $wxcode=session('wxcode');
            //   dump($wxcode);
            if(empty($wxcode)){
                $this->islogin();
            }
        }

        $url = $this->request->param('back')?$this->request->param('back'):'';
        //  dump($this->request->param());die;
        $this->assign('url', $url);
        $this->assign('type',cmf_is_wechat()?3:1 );
        $this->assign('back', $this->request->param('back')?$this->request->param('back'):'');
        $this->assign('description', '注册会员');
        $this->assign('keywords', '虞心荟');
        $this->assign('title', '填写手机号-虔心荟');

//        $back=$this->request->param();
//        dump($back);die;

        return $this->fetch();
    }
    public function data(){
        //ump($this->request);die;
        if (session('token')){
//            dump(session('token'));die;
            $this->redirect('/index');
            exit();
        }
//        dump($this->request->param());die;
        $mobile=$this->request->param('mobile');
        $code=$this->request->param('mcode');
        if (empty($mobile) || empty($code)){
            header('location:./404.html');
            exit();
        }
        $wxcode=session('wxcode');
        if (cmf_is_wechat() ){
            $wxcode=session('wxcode');
            //   dump($wxcode);
            if(empty($wxcode)){
                $this->islogin();
            }
        }
        $list=Db::name('question')->where(['E_IsDelete'=>1])->select();
//        dump($list);die;
        if(cmf_is_wechat()){
            $type=1;
        }else{
            $type=2;
        }
        $this->assign('list',$list);
        $this->assign('type',$type);
        $this->assign('incode',session('incode')?session('incode'):'');
        $this->assign('mobile', $this->request->param('mobile'));
        $this->assign('mcode', $this->request->param('mcode'));
        $this->assign('back',GetAllURL());
        $this->assign('openid', $wxcode?$wxcode:'');
        $this->assign('backurl', $this->request->param('back'));
        $this->assign('description', '注册会员');
        $this->assign('keywords', '虞心荟');
        $this->assign('title', '填写资料-虔心荟');
        $this->assign('item',cmf_is_wechat());
        return $this->fetch();
    }
    public function reg_success(){
//        if (session('token')){
////            dump(session('token'));die;
//            $this->redirect('/index');
//            exit();
//        }
//        preg_match('(?=<div>),*(?=</div>)','fdsafdsaf');
        $token=$this->request->param('token');
        if (empty($token)){
            header('location:./404.html');
        }else{
            session('token',$token);
        }
        $back=$this->request->param('back')?$this->request->param('back'):'';
        $this->assign('back',$back);
        $this->assign('count',$this->request->param('count'));
        $this->assign('description', '注册会员');
        $this->assign('keywords', '虞心荟');
        $this->assign('title', '注册成功-虔心荟');
        return $this->fetch();
    }
}
