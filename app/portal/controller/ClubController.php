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

class ClubController extends HomeBaseController
{
    public function index()
    {
        $this->assign('club', '1');
        $this->assign('description', '俱乐部');
        $this->assign('keywords', '虞心荟');
        $this->assign('tid',time());
        return $this->fetch();
    }

}
