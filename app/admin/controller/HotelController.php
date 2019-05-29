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

use app\admin\model\HotelModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;
use think\Validate;

class HotelController extends AdminBaseController
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
     * 房间列表
     */

    public function home()
    {
        $hotel=new HotelModel();
        $list=Db::name('hotel_home')->alias('a')->join('hotel_home_type b','a.E_Type=b.id')->field('a.*,b.E_Name type')->paginate();
        $this->assign('list',$list->items());
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    public function home_add(){
        if ($this->request->isAjax()){
            $param=$this->request->param();
           // dump($param);die;
            $validate=new Validate();
            $validate->rule([
                'E_Name'=>'require',
                'E_Num'=>'require',
                'E_Type'=>'require',
                'E_Url'=>'require',
                'E_Price'=>'require',
                'E_Des'=>'require',
            ]);
            $validate->message([
                'E_Name.require'=>'房间名称不能为空',
                'E_Num.require'=>'房间编号不能为空',
                'E_Type.require'=>'房间类型不能为空',
                'E_Url.require'=>'房间图片不能为空',
                'E_Price.require'=>'房间价格不能为空',
                'E_Des.require'=>'房间描述不能为空',
            ]);
            $result=$validate->check($param);
            if ($result===false){
                $this->error($validate->getError());
            }
            $max=Db::name('hotel_home')->max('id');
            if($max==0){
                $param['E_Code']='home_1_'.cmf_random_string(5);
            }else{
                $max+1;
                $param['E_Code']='home_'.$max.'_'.cmf_random_string(5);
            }
            $param['E_CreateUser']=session('name');
            $param['E_CreateDate']=time();
            Db::name('hotel_home')->insert($param);
            $this->success('添加成功','home');

        }

        $type=Db::name('hotel_home_type')->select();
        $this->assign('type',$type);
        return $this->fetch();
    }
    public function home_type(){
       // $hotel=new HotelModel();

        $list=Db::name('hotel_home_type')->paginate();
        $this->assign('list',$list->items());
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    public function home_type_add(){
           // dump($this->request->isAjax());die;
        if ($this->request->isAjax()){

            $param=$this->request->param();
            $param['E_CreateUser']=session('name');
            $param['E_CreateDate']=time();
            Db::name('hotel_home_type')->insert($param);
            $this->success('新增成功','home_type');
            exit();
        }
        return $this->fetch();
    }
    public function home_type_edit(){
        if ($this->request->isAjax()){

            $param=$this->request->param();
            $param['E_UpdateUser']=session('name');
            $param['E_UpdateDate']=time();

            Db::name('hotel_home_type')->where(['id'=>$param['id']])->update($param);
            $this->success('修改','home_type');
            exit();
        }
        $id=$this->request->param('id','','intval');
        if($id){
            $find=Db::name('hotel_home_type')->where(['id'=>$id])->find();
            $this->assign('type',$find);
            return $this->fetch();
        }
    }
}
