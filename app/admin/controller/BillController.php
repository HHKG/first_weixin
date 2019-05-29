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

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;

class BillController extends AdminBaseController
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

    public function bill_list()
    {
        $userid = $this->request->param('user');
        $where = [];
        if (!empty($userid)) {
            $where = ['E_UserID' => $userid];
        }
        $billtime = $this->request->param('bill_time') ? $this->request->param('bill_time') : '';
        $settime = $this->request->param('set_time') ? $this->request->param('set_time') : '';
        $project = $this->request->param('project') ? $this->request->param('project') : '';
        $plan = $this->request->param('plan') ? $this->request->param('plan') : '';
        $plancode = $this->request->param('plancode') ? $this->request->param('plancode') : '';
        $userid=$this->request->param('userid')?$this->request->param('userid'):'';
//        $ordersn = $this->request->param('ordersn') ? $this->request->param('ordersn') : '';

        if (!empty($billtime)) {
            $where['E_BillDate'] = substr($billtime, 0, 7);
            $this->assign('bill_time', $billtime);
        }
        if (!empty($settime)) {
            $where['E_SettlementDate'] = substr($settime, 0, 7);
            $this->assign('set_time', $settime);
        }
//        if (!empty($ordersn)) {
//            $where['E_OrderSn'] = ['like', '%' . $ordersn . '%'];
//            $this->assign('ordersn', $ordersn);
//
//        }

        if (!empty($project)) {
            $where['E_ProjectName'] = ['like', '%' . $project . '%'];
            $this->assign('project', $project);

        }
        if (!empty($plan)) {
            $where['E_PlanName'] = ['like', '%' . $plan . '%'];
            $this->assign('plan', $plan);

        }
        if (!empty($plancode)) {
            $where['E_PlanCode'] = ['like', '%' . $plancode . '%'];
            $this->assign('E_PlanCode', $plancode);

        }
        if (!empty($userid)) {
            $where['E_UserID'] = $userid;
            $this->assign('userid', $userid);
        }
//            dump($this->request->param());
        $bills = Db::name('member_bill')->where($where)->order('E_CreateDate desc')
            ->field('E_UserID,from_unixtime(E_CreateDate,"%Y-%m-%d") E_CreateDate,E_State,E_OrderSn,(E_PlanPrice*0.01) E_PlanPrice,(E_BillPrice*0.01) E_BillPrice,E_PlanName,E_ProjectName,E_BillDay,E_BillDate,E_SettlementDay,E_SettlementDate,E_Scale,E_SumPeriods,E_Periids,E_Abstract')
            ->paginate($this->limit, false, ['query' => $this->request->param()]);
        $this->assign('bills', $bills->items());
        $this->assign('page', $bills->render());
        return $this->fetch();

    }

    public function settlement_list()
    {
        $settime = $this->request->param('set_time') ? $this->request->param('set_time') : '';
        $project = $this->request->param('project') ? $this->request->param('project') : '';
        $plan = $this->request->param('plan') ? $this->request->param('plan') : '';
        $ordersn = $this->request->param('ordersn') ? $this->request->param('ordersn') : '';
        $where = [];

        if (!empty($settime)) {
            $where['E_Month'] = substr($settime, 0, 7);
            $this->assign('set_time', $settime);
        }
        if (!empty($ordersn)) {
            $where['E_OrderSn'] = ['like', '%' . $ordersn . '%'];
            $this->assign('ordersn', $ordersn);

        }
        if (!empty($project)) {
            $where['E_ProjectName'] = ['like', '%' . $project . '%'];
            $this->assign('project', $project);

        }
        if (!empty($plan)) {
            $where['E_PlanName'] = ['like', '%' . $plan . '%'];
            $this->assign('plan', $plan);
        }
//            dump($where);
        $sets = Db::name('member_settlement')->where($where)->order('E_CreateDate desc')
            ->field('E_UserID,from_unixtime(E_CreateDate,"%Y-%m-%d") E_CreateDate,E_OrderSn,(E_PlanPrice*0.01) E_PlanPrice,(E_BillPrice*0.01) E_BillPrice,E_Month,E_Day,E_Abstract,E_ProjectName,E_PlanName,E_Month,E_Day')
            ->paginate($this->limit, false, ['query' => $this->request->param()]);
        $this->assign('sets', $sets->items());
        $this->assign('page', $sets->render());
        return $this->fetch();
    }

    public function coupon_list()
    {
        $userid = $this->request->param('user');
        $where = [];
        if (!empty($userid)) {
            $where = ['E_UserID' => $userid];
        }
        $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
        $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
        $project = $this->request->param('project') ? $this->request->param('project') : '';
        $plan = $this->request->param('plan') ? $this->request->param('plan') : '';
        $ordersn = $this->request->param('ordersn') ? $this->request->param('ordersn') : '';
        $plancode = $this->request->param('plancode') ? $this->request->param('plancode') : '';
//            $bill_id=Db::name('member_house_bill')->where(['E_OrderSn'=>$ordersn])->value('id');


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
                $where['E_CreateTime'] = ['lt', strtotime($end) + 24 * 3600];
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
        if (!empty($plancode)) {
            $where['E_PlanCode'] = ['like', '%' . $plancode . '%'];
            $this->assign('plancode', $plancode);
        }
//            dump($where);
        $coupon = Db::name('member_house_coupon')->where($where)->order('E_CreateTime desc')->field('from_unixtime(E_CreateTime,"%Y-%m-%d") E_CreateTime,E_SN,E_OrderSn,(E_Price*0.01) E_Price,(E_HasPrice*0.01) E_HasPrice,E_LastDate,E_Periods,E_CouponImg,E_ProjectName,E_PlanName')->paginate($this->limit);
        $this->assign('sets', $coupon->items());
        $this->assign('user', $userid);
        $this->assign('page', $coupon->render());
        return $this->fetch();
    }

}
