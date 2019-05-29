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

use app\portal\model\MemberModel;
use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use think\Db;

class MemberController extends HomeBaseController
{
    public function _initialize()
    {

        parent::_initialize();
        $this->islogin();
    }

    public function index()
    {
       // dump($this->request->param());die;

        //dump(session('pattern'));
        $this->assign('member', '1');
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '会员中心-虔心荟');
        return $this->fetch();

    }
    public function myorders(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '我的订单-虔心荟');
        return $this->fetch();
    }
    public function rebate(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '返现记录-虔心荟');
        return $this->fetch();
    }
    public function recharge(){
        $grade=$this->request->param('rank')?$this->request->param('rank'):1;
        $this->assign('grade', $grade);
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '充值升级-虔心荟');
        return $this->fetch();
    }
    public function rechargesuccess(){
        $sn=$this->request->param('sn');
        $this->assign('sn', $sn);
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '充值成功-虔心荟');
        return $this->fetch();
    }
    public function cash(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '现金余额-虔心荟');
        return $this->fetch();
    }
    public function bindback(){
        $name=$this->request->param('name');
        $this->assign('name', $name);
        $this->assign('description', '会员中心');

        $this->assign('keywords', '虔心荟');
        $this->assign('title', '绑定银行卡-虔心荟');
        return $this->fetch();
    }
    public function withdrawal(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '提现-虔心荟');
        return $this->fetch();
    }
    public function balance(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '收支明细-虔心荟');
        return $this->fetch();
    }
    public function consumption(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '消费金明细-虔心荟');
        return $this->fetch();
    }
    public function certificate(){

        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '会员证书-虔心荟');
        return $this->fetch();
    }
    public function couponlist(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '住房券列表-虔心荟');
        return $this->fetch();
    }
    public function myplan(){

        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '我的计划-虔心荟');
        return $this->fetch();
    }
    public function authentication(){
        $token=session('token');
        $isauth=Db::name('member_token')->alias('a')->where(['a.token'=>$token])->join('member b','a.user_id=b.E_UserID')->value('b.E_IsInvestor');
        if ($isauth==2){
            $this->redirect('/cash');
        }
//          if (data.code == 9201) {
//                   console.log(data.code);
//
//<!--               		window.location.href='/member';-->
//<!--               }-->

        $this->assign('description', '会员中心');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '实名认证-虔心荟');
        return $this->fetch();
    }
    public function imitate(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '模拟模式');
        $this->assign('title', '模拟模式-虔心荟');
        return $this->fetch();
    }
    public function myinvite(){
        $this->assign('description', '会员中心');
        $this->assign('keywords', '我的邀请');
        $this->assign('title', '我的邀请-虔心荟');
        return $this->fetch();
    }
    public function forgetpwd()
    {
        return $this->fetch();
    }
    public function checkforgetpwd()
    {
        $re['msg'] = '修改密码成功！';
        $re['code'] = 0;
        $param = $this->request->param();
        if (empty($param['pwd'])) {
            $re['msg'] = '请传入新密码！';
            $re['code'] = 1;
            goto end;
        }
        $res = $this->checkedmobilecode($param['mobile'], $param['code']);
        if ($res['code'] != 0) {
            $re = $res;
            goto end;
        }
        $mem = new MemberModel();
        $mem->E_PWDMD5 = cmf_encryption($param['pwd']);
        $mem->E_PWD = $param['pwd'];
        $where = ['E_Mobile' => $param['mobile']];
        $mem->where($where)->save();
        if (!$mem->getLastInsID()) {
            $re['msg'] = '插入失败！';
            $re['code'] = 1;
            goto end;
        }
        end:
        return $re;

    }
    public function getdetailedinfo(){
        $code=$this->request->param('usercode');
        if (empty($code)){
            $this->error('非法操作');
        }
        $mem=new MemberModel();
        $where=['E_UserCode'=>$code];
        $field='E_Mobile,E_Name,E_Sex,E_HeadImg,E_Email,E_IsInvestor,E_IsIdentityCard';
        $info=$mem->where($where)->field($field)->find();
        if (!$info){
            $this->error('用户不存在！');
        }
        $this->assign('info',$info);
        return $this->fetch();

    }
    public function coupons(){
        // $this->assign('project',$project);
        $this->assign('description', '首页');
        $this->assign('keywords', '虔心荟');
        $this->assign('title', '邀请好友免费领￥500元住房券-虔心荟');
        return $this->fetch();
    }

    /**
     *积分列表
     */
    public function mypoints(){

    }
    /**
     *积分说明
     */
    public function pointsintroduce(){

    }

}
