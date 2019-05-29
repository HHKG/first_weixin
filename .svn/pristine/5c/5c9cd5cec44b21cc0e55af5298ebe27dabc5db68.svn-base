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

use app\admin\model\MemberModel;
use app\admin\model\OrderModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;

class OrderController extends AdminBaseController
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

    /**
     * 订单列表
     */

    public function index()
    {
        $mem= new OrderModel();
        $where=['a.E_Type'=>2];
        $start_time=$this->request->param('start_time')?$this->request->param('start_time'):'';
        $end_time=$this->request->param('end_time')?$this->request->param('end_time'):'';
        $ordersn=$this->request->param('ordersn')?$this->request->param('ordersn'):'';
        $status=$this->request->param('status')?$this->request->param('status'):'';
        $userid=$this->request->param('userid')?$this->request->param('userid'):'';
        $mobile = $this->request->param('mobile') ? $this->request->param('mobile') : '';
//        dump($this->request->param());
//        dump($start_time);
//        die;
        if (!empty($start_time) && !empty($end_time)){
            $where['a.E_CreateDate']=[['gt',strtotime($start_time)],['lt',strtotime($end_time)+24*3600]];
            $this->assign('start_time', $start_time);
            $this->assign('end_time', $end_time);
        }else{
            if (!empty($end_time)){
                $where['a.E_CreateDate']=['lt',strtotime($end_time)+24*3600];
                $this->assign('end_time', $end_time);
            }
            if (!empty($start_time)){
                $where['a.E_CreateDate']=['gt',strtotime($start_time)];
                $this->assign('start_time', $start_time);
            }
        }
        if (!empty($ordersn)){
            $where['a.E_OrderSn']=['like','%'.$ordersn.'%'];
            $this->assign('ordersn', $ordersn);
        }
        if (!empty($status)){
            $where['a.E_PayState']=$status;

        }
        if (!empty($mobile)) {
            $where['c.E_Mobile'] = ['like', '%' . $mobile . '%'];
            $this->assign('mobile', $mobile);
        }
        if (!empty($userid)) {
        $where['c.E_UserID'] = $userid;
        $this->assign('userid', $userid);
    }
     //   dump($where);
//        dump($where);die;
        $field='a.E_UserID,a.E_Commet,from_unixtime(a.E_Paytime,"%Y-%m-%d %H:%i:%s") E_Paytime,from_unixtime(a.E_CreateDate,"%Y-%m-%d %H:%i:%s") E_CreateDate,a.E_OrderSn,a.E_PayState,a.E_PayType,a.E_PayNum,(a.E_OrderPrice*0.01) E_OrderPrice,(a.E_DiscountPrice*0.01)E_DiscountPrice,(a.E_PayPrice*0.01) E_PayPrice,(b.E_Money*0.01) E_Money,b.E_BuyNum,b.E_PlanName,b.E_PlanCode,b.E_ProjectCode,b.E_ProjectName,c.E_Name,c.E_Mobile';
        $join=[
            ['order_good b','a.E_OrderSn=b.E_OrderSn'],
            ['member c','a.E_UserID=c.E_UserID'],
        ];
        $list=$mem->alias('a')->where($where)->join($join)->order('E_CreateDate desc')->field($field)->paginate($this->limit);
        // dump($mem->getLastSql());
        if ($plan=$this->request->param('ToEXcel')=='导出'){
            $topname=['用户编号','创建时间','订单编号','订单状态','支付类型','支付时间','支付流水号','订单价格','优惠价格','实付价格','购买数量','方案名称','方案编号','计划编号','计划名称','用户名'];

            $cellname=['E_UserID','E_CreateDate','E_OrderSn','E_PayState','E_PayType','E_Paytime','E_PayNum','E_OrderPrice','E_DiscountPrice','E_PayPrice','E_BuyNum','E_PlanName','E_PlanCode','E_ProjectCode','E_ProjectName','E_Name'];
            $this->createexecl('order',$cellname,$list,$topname);
//            die;
        }
        $this->assign('status', $status);
        $this->assign('list', $list->items());
        $this->assign('page', $list->render());
        return $this->fetch();
    }
    /**
     * 订单列表
     */

    public function rechange()
    {
        $mem= new OrderModel();
        $where=['a.E_Type'=>1];
        $start_time=$this->request->param('start_time')?$this->request->param('start_time'):'';
        $end_time=$this->request->param('end_time')?$this->request->param('end_time'):'';
        $ordersn=$this->request->param('ordersn')?$this->request->param('ordersn'):'';
        $status=$this->request->param('status')?$this->request->param('status'):'';
        $userid=$this->request->param('userid')?$this->request->param('userid'):'';
        $mobile = $this->request->param('mobile') ? $this->request->param('mobile') : '';
//        dump($this->request->param());
//        dump($start_time);
//        die;
        if (!empty($start_time) && !empty($end_time)){
            $where['a.E_CreateDate']=[['gt',strtotime($start_time)],['lt',strtotime($end_time)+24*3600]];
            $this->assign('start_time', $start_time);
            $this->assign('end_time', $end_time);
        }else{
            if (!empty($end_time)){
                $where['a.E_CreateDate']=['lt',strtotime($end_time)+24*3600];
                $this->assign('end_time', $end_time);
            }
            if (!empty($start_time)){
                $where['a.E_CreateDate']=['gt',strtotime($start_time)];
                $this->assign('start_time', $start_time);
            }
        }
        if (!empty($ordersn)){
            $where['a.E_OrderSn']=['like','%'.$ordersn.'%'];
            $this->assign('ordersn', $ordersn);
        }
        if (!empty($status)){
            $where['a.E_PayState']=$status;

        }
        if (!empty($mobile)) {
            $where['c.E_Mobile'] = ['like', '%' . $mobile . '%'];
            $this->assign('mobile', $mobile);
        }
        if (!empty($userid)) {
            $where['c.E_UserID'] = $userid;
            $this->assign('userid', $userid);
        }
     //  dump($where);
        $field='a.E_UserID,a.E_Commet,from_unixtime(a.E_Paytime,"%Y-%m-%d %H:%i:%s") E_Paytime,from_unixtime(a.E_CreateDate,"%Y-%m-%d %H:%i:%s") E_CreateDate,a.E_OrderSn,a.E_PayState,a.E_PayType,a.E_PayNum,(a.E_OrderPrice*0.01) E_OrderPrice,(a.E_DiscountPrice*0.01)E_DiscountPrice,(a.E_PayPrice*0.01) E_PayPrice,(b.E_Money*0.01) E_Money,b.E_BuyNum,b.E_PlanName,b.E_PlanCode,b.E_ProjectCode,b.E_ProjectName,c.E_Name,c.E_Mobile';
        $join=[
            ['order_good b','a.E_OrderSn=b.E_OrderSn'],
            ['member c','a.E_UserID=c.E_UserID'],
        ];
        $list=$mem->alias('a')->where($where)->join($join)->order('E_CreateDate desc')->field($field)->paginate($this->limit);
        // dump($mem->getLastSql());

        $this->assign('status', $status);
        $this->assign('list', $list->items());
        $this->assign('page', $list->render());
        return $this->fetch();
    }
    public function remark(){
        $sn=$this->request->param('sn');
        $remark=$this->request->param('remark');
        if (!empty($sn)){
            $this->assign('sn',$sn);
            $this->assign('remark',$remark);
            return $this->fetch();
        }
    }
    public function remark_post(){
        $sn=$this->request->post('sn');
        $remark=$this->request->post('remark');
        $newremark=$this->request->post('comment');
        if (!empty($newremark)){
            $data['E_Commet']=empty($remark)?$newremark:$remark.','.$newremark;
            $data['E_UpdateUser']=session('name');
            $data['E_UpdateDate']=time();
            $order=new OrderModel();
            $order->save($data,['E_OrderSn'=>$sn]);
            //dump($order->getLastSql());die;
            $this->success('修改成功');
        }else{
            $this->error('未作修改');
        }

    }
}
