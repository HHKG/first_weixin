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

class QuestionController extends AdminBaseController
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
        $list=Db::name('question')->where(['E_IsDelete'=>1])->paginate(10);
//        foreach ($list as $v){
//            $v['answers']=unserialize($v['E_Answer']);
//
//        }
//dump($list);die;
        $this->assign('question',$list);
        return $this->fetch();
    }
    public function add(){
        return $this->fetch();
    }
    public function add_post(){
        $param=$this->request->param();
        $validate=$this->validate($param,'Question');
        if ($validate!==true){
            $this->error($validate);
        }
        $data['E_Title']=$param['E_Title'];
        unset($param['E_Title']);
        $data['E_Answer']=serialize($param);
        $data['E_CreateUser']=session('name');
        $data['E_CreateDate']=time();
        Db::name('question')->insert($data);
        $this->success('新增成功！','index');
    }
    public function edit(){
        $id=$this->request->param('id','','intval');
        if ($id){
            $find=Db::name('question')->where(['id'=>$id])->find();
            $this->assign('question',$find);
            return $this->fetch();
        }
    }
    public function edit_post(){
        $param=$this->request->param();
        $validate=$this->validate($param,'Question');
        if ($validate!==true){
            $this->error($validate);
        }
        $data['E_Title']=$param['E_Title'];
        $id=$param['id'];
        unset($param['E_Title']);
        unset($param['id']);
        $data['E_Answer']=serialize($param);
        $data['E_UpdateUser']=session('name');
        $data['E_UpdateDate']=time();
        Db::name('question')->where(['id'=>$id])->update($data);
        $this->success('修改成功！','index');
    }
    public function delete(){
        $id=$this->request->param('id');
        if ($id){
            Db::name('question')->where(['id'=>$id])->update(['E_IsDelete'=>2]);
        }
        $this->success('删除成功！','index');
    }
}
