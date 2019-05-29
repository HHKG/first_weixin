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

class PlanController extends AdminBaseController
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

    public function index()
    {
        $where = [];
        $userid = $this->request->param('user')? $this->request->param('user') : '';
        if (!empty($userid)) {
            $where['a.E_UserID'] =$userid;
            $this->assign('user', $userid);
        }
        $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
        $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
        $project = $this->request->param('project') ? $this->request->param('project') : '';
        $plan = $this->request->param('plan') ? $this->request->param('plan') : '';

        if (!empty($start) && !empty($end)) {
            $where['a.E_CreateTime'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
            $this->assign('start_time', $start);
            $this->assign('end_time', $end);
        } else {
            if (!empty($start)) {
                $where['a.E_CreateTime'] = ['gt', strtotime($start)];
                $this->assign('start_time', $start);
            }
            if (!empty($end)) {
                $where['a.E_CreateTime'] = ['lt', strtotime($end) + 24 * 3600];
                $this->assign('end_time', $end);
            }
        }
        if (!empty($project)) {
            $where['a.E_ProjectName'] = ['like', '%' . $project . '%'];
            $this->assign('project', $project);

        }
        if (!empty($plan)) {
            $where['a.E_PlanName'] = ['like', '%' . $plan . '%'];
            $this->assign('plan', $plan);

        }
//        dump($where);
        $plans = Db::name('member_plan')->alias('a')->join('member_plan_coupon b','b.E_OrderSn=a.E_OrderSn')->where($where)->order('a.E_CreateTime desc')
            ->field('from_unixtime(a.E_CreateTime,"%Y-%m-%d") E_CreateTime,a.E_UserID,a.E_OrderSn,(a.E_PlanPrice*0.01) E_PlanPrice,(a.E_Value*0.01) E_Value,a.E_PlanCode,a.E_BuyNum,a.E_PlanName,a.E_ProjectName,a.E_Cycle,a.E_BillCycle,a.E_BillDay,a.E_SettlementCycle,a.E_SettlementDay,a.E_BillDate,a.E_SettlementDate,a.E_Scale,a.E_SumPeriods,a.E_AreadlyPeriids,b.E_Scale couponscale,b.E_SumPeriods couponSumPeriods,b.E_AreadlyPeriids souponAreadlyPeriids,b.E_CouponCycle')
            ->paginate($this->limit, false, ['query' => $this->request->param()]);
        $this->assign('plans', $plans->items());
        $this->assign('page', $plans->render());

        return $this->fetch();

    }
}
