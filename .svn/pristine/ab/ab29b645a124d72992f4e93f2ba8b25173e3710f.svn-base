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
namespace app\admin\controller;

use app\admin\model\CouponModel;
use app\admin\model\CouponTypeModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;
use think\db\Query;
use think\Validate;

class CouponController extends AdminBaseController
{

    public function _initialize()
    {
        $adminSettings = cmf_get_option('admin_settings');
        if (empty($adminSettings['admin_password']) || $this->request->path() == $adminSettings['admin_password']) {
            $adminId = cmf_get_current_admin_id();
            if (empty($adminId)) {
                session("__LOGIN_BY_CMF_ADMIN_PW__", 1);//设置后台登录加密码
            }
        }

        parent::_initialize();
    }
    public function index(){
        $coupon=new CouponModel();
        $list=$coupon->GetCouponList();


        $this->assign('coupons',$list);

        return $this->fetch();
    }
    public function add(){
        $coupontype=new CouponTypeModel();
        $type=$coupontype->GetAllCoupon();
        $this->assign('type',$type);
        return $this->fetch();
    }
    public  function add_post(){
        $param=$this->request->param();
        $validate=$this->validate($param,'Coupon');
        if ($validate!==true){
            $this->error($validate);
        }
        $param['E_SendStartTime']=strtotime($param['E_SendStartTime']);
        $param['E_SendEndTime']=strtotime($param['E_SendEndTime']);
        $param['E_CreateUser']=session('name');
        $param['E_CreateDate']=time();
        Db::name('coupon')->insert($param);
        $this->success('新增成功！','index');

    }
    public function edit(){
        $id=$this->request->param('id');
        if ($id){
            $find=Db::name('coupon')->where(['id'=>$id])->find();
            $coupontype=new CouponTypeModel();
            $type=$coupontype->GetAllCoupon();
            $this->assign('type',$type);
            $this->assign('coupon',$find);
            return $this->fetch();
        }

    }
    public function edit_post(){
        $param=$this->request->param();
        $validate=$this->validate($param,'coupon');
        if ($validate!==true){
            $this->error($validate);
        }
        $param['E_SendStartTime']=strtotime($param['E_SendStartTime']);
        $param['E_SendEndTime']=strtotime($param['E_SendEndTime']);
        $param['E_UpdateUser']=session('name');
        $param['E_UpdateDate']=time();
        Db::name('coupon')->where(['id'=>$param['id']])->update($param);
        $this->success('修改成功!','index');
    }
    public function delete(){
        $id=$this->request->param('id');
        if ($id){
            Db::name('coupon')->where(['id'=>$id])->update(['E_IsDelete'=>2]);
            $this->success('删除成功！','index');
        }
    }
    public function type_index()
    {
        $coupon=new CouponTypeModel();
        $coupons=$coupon->GetAllCoupon();
//        dump($coupons);die
        $this->assign('coupons', $coupons);
        return $this->fetch();
    }
    public function type_add(){
        return $this->fetch();
    }
    public function type_add_post(){
        $param=$this->request->param();

        $result=$this->validate($param,'CouponType');
        if ($result!==true){
            $this->error($result);
        }
        Db::name('coupon_type')->insert($param);
        $this->success('新增成功！','coupon/type_index');

    }
    public function type_edit(){
        $id=$this->request->param('id');
        $find=Db::name('coupon_type')->where(['id'=>$id])->find();
        if ($find){
            $this->assign('coupon',$find);
            return $this->fetch();
        }else{
            $find->error('类型不存在','coupon/type_index');
        }
    }
    public function type_edit_post(){
        $param=$this->request->param();

        $result=$this->validate($param,'CouponType');
        if ($result!==true){
            $this->error($result);
        }

        Db::name('coupon_type')->where(['id'=>$param['id']])->update($param);
        $this->success('修改成功！','coupon/type_index');
    }
    public function type_delete(){
        $id=$this->request->param('id');
        if ($id){
            Db::name('coupon_type')->delete(['id'=>$id]);
            $this->success('删除成功！','coupon/type_index');
        }
    }
    public function house(){
        $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
        $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
        $project = $this->request->param('project') ? $this->request->param('project') : '';
        $plan = $this->request->param('plan') ? $this->request->param('plan') : '';
        $ordersn = $this->request->param('ordersn')?$this->request->param('ordersn'):'';
//
//            $bill_id=Db::name('member_house_bill')->where(['E_OrderSn'=>$ordersn])->value('id');
        $where = [];

        if (!empty($start) && !empty($end)) {
            $where['E_CreateTime'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
            $this->assign('start_time', $start);
            $this->assign('end_time', $end);
        } else {
            if (!empty($start)) {
                $where['E_CreateTime'] = ['gt', strtotime($start)];
                $this->assign('start_time', $start);
            }
            if (!empty($end)) {
                $where['E_CreateTime'] = ['lt', strtotime($end)+ 24 * 3600];
                $this->assign('end_time', $end);
            }
        }
        if (!empty($project)) {
            $where['E_ProjectName'] = ['like', '%' . $project . '%'];
            $this->assign('project', $project);

        }
        if (!empty($ordersn)) {
            $where['E_OrderSn'] = ['like', '%' . $ordersn . '%'];
            $this->assign('ordersn', $ordersn);

        }
        if (!empty($plan)) {
            $where['E_PlanName'] = ['like', '%' . $plan . '%'];
            $this->assign('plan', $plan);
        }
//            dump($this->request->param());
        $coupon = Db::name('member_house_coupon')->where($where)->order('E_CreateTime desc')->field('from_unixtime(E_CreateTime,"%Y-%m-%d") E_CreateTime,E_SN,E_OrderSn,(E_Price*0.01) E_Price,(E_HasPrice*0.01) E_HasPrice,E_LastDate,E_Periods,E_CouponImg,E_ProjectName,E_PlanName')
                                                ->paginate($this->limit,false,['query'=>$this->request->param()]);
        $this->assign('sets', $coupon->items());
        $this->assign('page', $coupon->render());
        return $this->fetch();
        return $this->fetch();
    }
}
