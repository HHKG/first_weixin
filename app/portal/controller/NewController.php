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

class NewController extends HomeBaseController
{
    public function index()
    {
        $new=new NewModel();
        $list=$new->field('E_Title,E_Subtitle,E_Img,E_From_name,E_From_img,E_Create_time')->paginate($this->limit);
        $this->assign('list',$list->items());
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    public function n_show(){
        $id=$this->request->param('id');
        if ($id){
            $find=NewModel::get($id);
            if (!$find)$this->error('非法参数！','New/index');
            $this->assign('find',$find);
            return $this->fetch();
        }
    }
}
