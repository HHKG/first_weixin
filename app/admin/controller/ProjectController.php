<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6
 * Time: 14:45
 */

namespace app\admin\controller;

use app\admin\model\ProjectplanModel;
use cmf\lib\sys\TanLan;
use cmf\lib\sys\ViewHlep;
use think\db;
use cmf\controller\AdminBaseController;
use app\admin\model\ProjectModel;
use think\Request;
use think\Validate;


class ProjectController extends AdminBaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {


        $param = $this->request->param();

        $where = 'E_IsDelete=0 ';
        if (!empty($param['start_time'])) $where = $where . " and a.E_Create_time >= " . strtotime($param['start_time']);
        if (!empty($param['end_time'])) $where = $where . " and a.E_Create_time <= " . strtotime($param['end_time']);
        if (!empty($param['keyword'])) $where = $where . " and a.E_Title like '%" . $param['keyword'] . "%'";
        if (!empty($param['address'])) $where = $where . " and a.E_Address like '%" . $param['address'] . "%'";
        if (!empty($param['type'])) $where = $where . " and a.E_Type = " . $param['type'];
        if (!empty($param['status'])) $where = $where . " and a.E_Status = " . $param['status'];
        $project_db = new ProjectModel();
        $field = '*';

        $list = $project_db->where($where)->field($field)->order('E_Create_time desc')->paginate(10);
        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('address', isset($param['address']) ? $param['address'] : '');
        $this->assign('type', isset($param['type']) ? $param['type'] : '');
        $this->assign('status', isset($param['status']) ? $param['status'] : '');
        $project_codes = implode(',', array_column($list->toArray()['data'], 'E_Code'));
        $plan = Db::name('project_plan')->where(['E_ProjectCode' => ['in', $project_codes]])->field('E_ProjectCode,E_Code,E_Title')->select();

        $this->assign('plan', $plan);
        $this->assign('projects', $list->items());
        $this->assign('page', $list->render());

        return $this->fetch();

        echo '列表';
    }

    public function post_edit_save()
    {
//        https://windows.php.net/download#php-7.0-nts-VC14-x64
    }

    public function publish()
    {
        $param = $this->request->param();
        $where = '1=1';
        if (!empty($param['start_time'])) $where = $where . " and a.create_time >= " . strtotime($param['start_time']);
        if (!empty($param['end_time'])) $where = $where . " and a.create_time <= " . strtotime($param['end_time']);
        if (!empty($param['keyword'])) $where = $where . " and a.title like '%" . $param['keyword'] . "%'";
        if (!empty($param['address'])) $where = $where . " and a.address like '%" . $param['address'] . "%'";
        if (!empty($param['type'])) $where = $where . " and a.type = " . $param['type'];
        if (!empty($param['status'])) $where = $where . " and a.project_status = " . $param['status'];

        $field = "a.*,b.mobile,b.user_login";
        $publishdb = Db::name('project_register');
        $list = $publishdb->alias('a')->join('user b', 'a.user_id=b.id ')->where($where)->field($field)->order('a.id desc')->paginate(10);

        $this->assign('type', isset($param['type']) ? $param['type'] : '');
        $this->assign('address', isset($param['address']) ? $param['address'] : '');
        $this->assign('status', isset($param['status']) ? $param['status'] : '');
        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');

        $this->assign('publishs', $list->items());
        $this->assign("page", $list->render());

        return $this->fetch();
    }

    public function add()
    {

        $this->assign('type', Db::name('project_type')->select());
        return $this->fetch();
    }

    public function addPost()
    {
//        $this->success('ok');
        $data = $this->request->param();
      //  dump($data);die;
        $image = $this->request->param('E_HeaderImg');
        $image='./upload/'.$image;

        $img=\think\Image::open($image);

        $img->thumb(800,1000)->save($image);

        //dump($data);die;
        $result = $this->validate($data, 'Project');

        if ($result !== true) {
            $this->error($result);
        }

        $project = new ProjectModel();
        $tagimg=$data['tagimg'];
        $tagname=$data['tagname'];
        $tag=$data['tag'];
//
        unset($data['tagimg']);
        unset($data['tagname']);
        unset($data['tag']);
      /*  $data['E_Tag']=serialize($data['tag']);
       // dump($data);die;
        unset($data['tag']);*/
        $projectcode=$project->getcode(20);
        $data['E_Code'] = $projectcode;
        $data['E_EndTime']=strtotime($data['E_EndTime']);

        $id = $project->addProject($data);

        if ($id == false) {
            $this->error('插入失败！');
        } else {
            if (!empty($tag)){
                foreach ($tag as $k=>$v){
                    $tdata['E_CreateUser']=session('name');
                    $tdata['E_CreateDate']=time();
                    $tdata['E_ProjectCode']=$projectcode;
                    $tdata['E_Tag']=$v;
                    $tdata['E_TagImg']=$tagimg[$k];
                    $tdata['E_TagName']=$tagname[$k];
                    Db::name('project_tag')->insert($tdata);
                }
            }
            $this->success('新增成功！', 'project/index');
        }
    }

    public function edit()
    {
        $code = $this->request->param('code');

        $project = new ProjectModel();
        $find = $project->GetOne($code);
//        dump($find);die;
//        $find = $project->where(['E_Code' => $code])->find();
//        dump($find
//        );die;
        if (!$find) {
            $this->error('项目不存在', 'project/index');
        }

        $tag=Db::name('project_tag')->where(['E_ProjectCode'=>$code])->select();
//        dump($tag->toArray());die;
        $this->assign('project', $find);
        $this->assign('tag', $tag?$tag:[[]]);
        $this->assign('type', Db::name('project_type')->select());
        return $this->fetch();
    }

    public function editPost()
    {
        $data = $this->request->param();
        $image = $this->request->param('E_HeaderImg');
        $image='./upload/'.$image;
       $img=\think\Image::open($image);
        $img->thumb(800,1000)->save($image);
       // dump($img);die;
        $result = $this->validate($data, 'Project');

        if ($result !== true) {
            $this->error($result);
        }
        $project = new ProjectModel();
        if (isset($data['tag'])){
            $tagimg=$data['tagimg'];
            $tagname=$data['tagname'];
            $tag=$data['tag'];
//
            unset($data['tagimg']);
            unset($data['tagname']);
            unset($data['tag']);
        }


////            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
////
        $code = $data['E_Code'];
        $data['E_EndTime']=strtotime($data['E_EndTime']);
        $id = $project->editProject($data, $code);

        if ($id == false) {
            $this->error('修改失败！');
        } else {
            Db::name('project_plan')->where(['E_ProjectCode'=>$code])->update(['E_Url'=>$data['E_HeaderImg']]);
            if (!empty($tag)){
                Db::name('project_tag')->where(['E_ProjectCode'=>$data['E_Code']])->delete();
                foreach ($tag as $k=>$v){
                    $tdata['E_CreateUser']=session('name');
                    $tdata['E_CreateDate']=time();
                    $tdata['E_ProjectCode']=$data['E_Code'];
                    $tdata['E_Tag']=$v;
                    $tdata['E_TagImg']=$tagimg[$k];
                    $tdata['E_TagName']=$tagname[$k];
                    Db::name('project_tag')->insert($tdata);
                }
            }
            $this->success('修改成功！', 'project/index');
        }
    }

    public function delete()
    {
        $id = $this->request->param('id', '', 'intval');
        if (!empty($id)) {
            $project = new ProjectModel();
            $result = $project->save(['E_IsDelete' => 1], ['id' => $id]);
            if ($result) {
                $this->success('删除成功！', 'project/index');
            }
        }
    }


    public function add_plan()
    {
        $code = $this->request->param('code');


        $project = new ProjectModel();
        $project_name = $project->where(['E_Code' => $code])->value('E_Abbreviation');
        if (!$project_name) {
            $this->error('项目不存在', 'project/index');
        }
//        dump($code);
//        dump($find);
//        die;
        $projectplan = new ProjectplanModel();
        $plancode = $projectplan->getcode(20);
//        dump($id);die;
        if ($project_name) {
            $this->assign('plan_code', $plancode);
            $this->assign('coupontype', Db::name('coupon_type')->field('id,E_Name')->select());
            $this->assign('project_name', $project_name);
            $this->assign('project_code', $code);
            return $this->fetch();
        }else{
                $this->error('新增方案错误', 'project/index');
            }
    }

    public function edit_plan()
    {
        $code = $this->request->param('code');

        $plancode = $this->request->param('plancode') ? $this->request->param('plancode') : '';
        $project = new ProjectModel();
        $project_name = $project->where(['E_Code' => $code])->value('E_Abbreviation');
        if (!$project_name) {
            $this->error('项目不存在', 'project/index');
        }

        if (!empty($plancode)) {
            $projectplan = new ProjectplanModel();
            $plan = $projectplan->GetPlan($plancode);
            if (!$plan) {
                $this->error('回报方案不存在', 'project/index');
            }
            $this->assign('plan', $plan);
        }
        $this->assign('coupontype', Db::name('coupon_type')->field('id,E_Name')->select());
        $this->assign('project_code', $code);
        $this->assign('project_name', $project_name);
        return $this->fetch();
    }

    public function scale()
    {
//        dump($this->request->param());
        $plan = $this->request->param('plan');
        $project = $this->request->param('project');
        if (!empty($project) && !empty($plan)) {
            $month = date('m', time());
//            dump($month);
            if ($month == 12) {
                $year = date('Y', strtotime("+1 year"));

            } else {
                $year = date('Y', time());
            }
            $info= Db::name('project_plan_scale')->where(['E_ProjectCode'=>$project,'E_PlanCode'=>$plan,'E_Year'=>$year])->find();
//        dump($info);die;
            $this->assign('year', $year);
            if ($info){
                $this->assign('info', $info);
            }
            $this->assign('plan', $plan);
            $this->assign('project', $project);
            return $this->fetch();
        }
    }

    public function scale_post()
    {
        $plan = $this->request->param('plan');
        $project = $this->request->param('project');
        $scale = $this->request->param();
//        dump($scale);die;
//        dump($validate->getError());
//             unset($scale['code']);
        if ($plan && $project) {
            $month = date('m', time());
            if ($month == 12) {
                $year = date('Y', strtotime("+1 year"));
            } else {
                $year = date('Y', time());
            }


            $data['E_ProjectCode'] = $project;
            $data['E_PlanCode'] = $plan;
            $data['E_Jan'] = $scale['E_Jan'];
            $data['E_Feb'] = $scale['E_Feb'];
            $data['E_March'] = $scale['E_March'];
            $data['E_April'] = $scale['E_April'];
            $data['E_May'] = $scale['E_May'];
            $data['E_June'] = $scale['E_June'];
            $data['E_July'] = $scale['E_July'];
            $data['E_August'] = $scale['E_August'];
            $data['E_Sept'] = $scale['E_Sept'];
            $data['E_Oct'] = $scale['E_Oct'];
            $data['E_Nov'] = $scale['E_Nov'];
            $data['E_Dec'] = $scale['E_Dec'];
            unset($scale['project']);
            unset($scale['plan']);
            unset($scale['update']);
            $data['E_Avg'] = array_sum($scale)/12;
           ;
//            $sum=;

            $one=Db::name('project_plan_scale')->where(['E_PlanCode'=>$plan,'E_Year'=>$year])->count();
            if($one==0){
                $data['E_Create_time'] = time();
                $data['E_Create_user'] = session('name');
                $data['E_Year'] = $year;
                Db::name('project_plan_scale')->insert($data);
            }else{
                $data['E_Create_time'] = time();
                $data['E_Update_user'] = session('name');
                Db::name('project_plan_scale')->where(['E_PlanCode'=>$plan,'E_Year'=>$year])->update($data);
            }
            $this->success('保存成功！');
        }
    }
    public function add_plan_post()
    {
        $param = $this->request->param();
        $validate = $this->validate($param, 'Projectplan');
        if ($validate != true) {
            $this->error($validate);
        }
        $projectplan = new ProjectplanModel();

        $param['E_Url']=Db::name('project')->where(['E_Code'=>$param['E_ProjectCode']])->value('E_HeaderImg');
        $result = $projectplan->addPlan($param);
        if ($result) {
            $this->success('方案增加成功！');
        } else {
            $this->success('增加失败！');
        }


    }

    public function edit_plan_post()
    {
        $param = $this->request->param();
        $validate = $this->validate($param, 'Projectplan');
        if ($validate != true) {
            $this->error($validate);
        }
        $projectplan = new ProjectplanModel();
        $code = $param['E_Code'];
        $result = $projectplan->editPlan($param, $code);
        if ($result) {
            $this->success('方案编辑成功！', 'project/index');
        } else {
            $this->success('增加失败！');
        }


    }


    public static function options($selected, $data = array(), $returns = 'string')
    {
        $out = array();
        is_array($selected) || $selected = array($selected);
        foreach ((array)$data as $k => $v) {
            $sel = in_array($k, $selected) ? 'selectded' : '';
            if (is_array($v)) {
                $out[$k] = '<option value="' . $k . '"' . $sel . '>' . $v['name'] . '</option>';
            } else {
                $out[$k] = '<option value="' . $k . '"' . $sel . '>' . $v . '</option>';
            }
        }
        return $returns == 'string' ? implode("\n", $out) : $out;
    }

    public function type_index()
    {
        $list = Db::name('project_type')->paginate(10);
        $this->assign('list', $list->items());
        return $this->fetch();
    }

    /**
     * 分类删除
     */
    public function type_delete()
    {
        $param = $this->request->param();
        $typedb = Db::name('project_type');
        if (isset($param['id'])) {
            $find_type = $typedb->where(['id' => $param['id']])->find();
            if (!empty($find_type)) {
                $result = $typedb->where(['id' => $param['id']])->delete();
                if ($result !== false) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败，请稍后再试！');
                }

            } else {
                $this->error('该分类不存在！');
            }
        }
        if (isset($param['ids'])) {
            $types = $typedb->where(['id' => ['in', $param['ids']]])->column('id');
            if (!array_diff($param['ids'], $types)) {
                $results = $typedb->where(['id' => ['in', $param['ids']]])->delete();
                $this->success('删除成功！');
            } else {
                $this->error('非法操作!');
            }
        }
    }

    /**
     * 分类增加
     */
    public function type_add()
    {
        return $this->fetch();
    }

    public function add_type_post()
    {
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $valate = new Validate(
                [
                    'E_Name' => 'require'
                ]
            );
            if (!$valate->check($param)) {
                $this->error('分类名补能为空！');
            }
            $type = Db::name('project_type');
            $type->insert($param);
            $this->success('增加成功！', url('project/type_index'));
        }

    }

    public function type_edit()
    {
        $id = $this->request->param('id');
        $find = Db::name('project_type')->find($id);

        $this->assign('type', $find);
        return $this->fetch();
    }

    public function edit_type_post()
    {
        if ($this->request->isPost()) {
            $param = $this->request->param();

            $valate = new Validate(
                [
                    'E_Name' => 'require'
                ]
            );
            if (!$valate->check($param)) {
                $this->error('分类名补能为空！');
            }
            $type = Db::name('project_type')->update($param);

            $this->success('编辑成功！', url('project/type_index'));
        }

    }

    public function select()
    {
        $this->assign('list', Db::name('project_type')->select());
        $this->assign('type', $this->request->param('id'));
        $this->assign('type', 1);
        return $this->fetch();
    }

    public function getc()
    {
        $list = Db::name('portal_category')->select();
        dump(ViewHlep::option('1', $list->toArray()));

        dump($list->toArray());
    }

    public function tl()
    {
        $n = 10;
        for ($i = 0; $i <= $n; $i++) {
            $weight = rand(1, 20);
            $price = rand(1, 10);
            $x[$i] = new TanLan($weight, $price);
        }
        $tl = new TanLan();
        $tl->tsorts($x);
        $tl->displays($x);
        echo '0-1背包最优为：';
        echo $tl->tanxin($x);

    }

    public function project_plan()
    {
        return $this->fecth();
    }
}