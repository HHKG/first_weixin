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
use think\queue\connector\Redis;


class OrderController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->islogin();
    }
    public function car()
    {

        $plan=$this->request->param('plan');
        if (empty($plan)){
            $this->redirect('/index');
        }
//        $options = [
//            'expire'     => 60,
//            'default'    => 'default',
//            'host'       => '127.0.0.1',
//            'port'       => 6379,
//            'password'   => '',
//            'select'     => 0,
//            'timeout'    => 0,
//            'persistent' => false
//        ];
//        $redis=new Redis($options);
//        $redis->
//        dump($redis);die;
        $this->assign('plan',$plan);
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '订单支付-虔心荟');
        return $this->fetch();
    }
    public function paysuccess(){

        $sn=$this->request->param('sn');
        if (empty($sn)){
            $this->redirect('/index');
        }
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '支付成功-虔心荟');
        $this->assign('sn',$sn);
        return $this->fetch();
    }
    public function myorder(){
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '我的订单-虔心荟');
        return $this->fetch();
    }
    public function settment(){
        return $this->fetch();
    }
}
