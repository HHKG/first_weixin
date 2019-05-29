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
use app\admin\model\MemberModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;
use think\db\Query;
use think\Validate;

class AssetController extends AdminBaseController
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
        $where=[];
       $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
       $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
       $key = $this->request->param('keyword') ? $this->request->param('keyword') : '';
       $name = $this->request->param('name') ? $this->request->param('name') : '';
       $userid=$this->request->param('userid')?$this->request->param('userid'):'';
       if (!empty($start) && !empty($end)) {
           $where['E_CreateDate'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
           $this->assign('start_time', $start);
           $this->assign('end_time', $end);
       } else {
           if (!empty($start)) {
               $where['E_CreateDate'] = ['gt', strtotime($start)];
               $this->assign('start_time', $start);
           }
           if (!empty($end)) {
               $where['E_CreateDate'] = ['lt', strtotime($end)];
               $this->assign('end_time', $end);
           }
       }
       if (!empty($name)) {
           $where['a.E_TrueName'] = ['like', '%' . $name . '%'];
           $this->assign('name', $name);

       }
       if (!empty($key)) {
           $where['a.E_Name'] = ['like', '%' . $key . '%'];
           $this->assign('key', $key);
       }
       if (!empty($userid)) {
           $where['a.E_UserID'] = $userid;
           $this->assign('userid', $userid);
       }
        $mem=new MemberModel();
        $list=$mem->alias('a')->where($where)->join('member_wallet b','a.E_UserID=b.E_UserID')->field('a.E_UserID,a.E_Name,a.E_TrueName,a.E_Grade,b.E_Balance,b.E_ConsumptionAmout,b.E_Consumption')->paginate($this->limit);
        $this->assign('list',$list->items());
        $this->assign('page',$list->render());
        return $this->fetch();
   }
   public function Balance(){
        $user=$this->request->param('user');
        if (!empty($user)){
            $where['E_UserID']=$user;
            $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
            $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
            if (!empty($start) && !empty($end)) {
                $where['E_CreateDate'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
                $this->assign('start_time', $start);
                $this->assign('end_time', $end);
            } else {
                if (!empty($start)) {
                    $where['E_CreateDate'] = ['gt', strtotime($start)];
                    $this->assign('start_time', $start);
                }
                if (!empty($end)) {
                    $where['E_CreateDate'] = ['lt', strtotime($end)];
                    $this->assign('end_time', $end);
                }
            }
            $list=Db::name('member_balance_bill')->where($where)->paginate($this->limit);
            $this->assign('list',$list->items());
            $this->assign('user',$user);
            $this->assign('page',$list->render());
            return $this->fetch();
        }
   }
   public function Consumption(){
       $user=$this->request->param('user');
       if (!empty($user)){
           $this->assign('user',$user);
           $where['E_UserID']=$user;
           $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
           $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
           if (!empty($start) && !empty($end)) {
               $where['E_CreateDate'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
               $this->assign('start_time', $start);
               $this->assign('end_time', $end);
           } else {
               if (!empty($start)) {
                   $where['E_CreateDate'] = ['gt', strtotime($start)];
                   $this->assign('start_time', $start);
               }
               if (!empty($end)) {
                   $where['E_CreateDate'] = ['lt', strtotime($end)];
                   $this->assign('end_time', $end);
               }
           }
//       dump($user);
           $list=Db::name('member_consumption_bill')->where($where)->paginate($this->limit);
           $this->assign('list',$list->items());
           $this->assign('page',$list->render());
           return $this->fetch();
       }
   }
   public function withdrawal(){
       $where=[];
       $start = $this->request->param('start_time') ? $this->request->param('start_time') : '';
       $end = $this->request->param('end_time') ? $this->request->param('end_time') : '';
       $name = $this->request->param('name') ? $this->request->param('name') : '';
       $userid=$this->request->param('userid')?$this->request->param('userid'):'';
       $status=$this->request->param('status')?$this->request->param('status'):0;
       if (!empty($start) && !empty($end)) {
           $where['E_CreateDate'] = [['gt', strtotime($start)], ['lt', strtotime($end) + 24 * 3600]];
           $this->assign('start_time', $start);
           $this->assign('end_time', $end);
       } else {
           if (!empty($start)) {
               $where['E_CreateDate'] = ['gt', strtotime($start)];
               $this->assign('start_time', $start);
           }
           if (!empty($end)) {
               $where['E_CreateDate'] = ['lt', strtotime($end)];
               $this->assign('end_time', $end);
           }
       }
       if (!empty($name)) {
           $where['a.E_UserName'] = ['like', '%' . $name . '%'];
           $this->assign('name', $name);

       }
       if (!empty($status)) {
           $where['a.E_State'] = $status;
       }
       $this->assign('status', $status);
       if (!empty($userid)) {
           $where['a.E_UserID'] = $userid;
           $this->assign('userid', $userid);
       }
       $mem_w=Db::name('member_withdrawal');
       $list=$mem_w->alias('a')->where($where)->field('a.E_UserID,a.E_UserName,a.E_CreateDate,a.E_Card,a.E_BankName,(a.E_Money*0.01) E_Money,a.E_State')->paginate($this->limit);
       $this->assign('list',$list->items());
       $this->assign('page',$list->render());
       return $this->fetch();
   }

}
///plugin/lyz_kefu_chat/index/kefulogin.html
/// /plugin/lyz_kefu_chat/index/chat.html