<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\speed\controller;

use app\speed\model\MemberModel;
use cmf\controller\ApiController;
use cmf\lib\juhe;
use think\Db;
use think\Exception;
use think\Validate;
use cmf\controller\HomeBaseController;


class MemberController extends ApiController
{
    public function _initialize()
    {
        $this->checklogin();
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    /**
     * 用户首页信息
     */
    public function index()
    {
        $re['msg'] = '成功！';
        $re['code'] = 1000;
        $re['data'] = array();

        $mem = new MemberModel();
        $where = ['a.E_UserCode'=>session('usercode')];

//        $field = 'a.E_Name,a.E_HeadImg,a.E_Email,a.E_IsInvestor,a.E_IsIdentityCard,count(b.E_Money) as coupon,count(c.id) as ordernum,d.E_Points,d.E_Balance,e.E_Name as grade,count(f.id) as iswx';
        $field = 'a.E_Name,a.E_HeadImg,a.E_Email,a.E_IsInvestor,a.E_IsIdentityCard,count(b.E_Money) as coupon,d.E_Points,d.E_Balance,e.E_Name as grade,count(f.id) as iswx';
        $join = [
            ['member_coupon b', 'a.E_UserCode=b.E_UserCode', 'LEFT'],
//            ['order c', 'a.E_UserCode=c.E_UserCode', 'LEFT'],
            ['member_wallet d', 'a.E_UserCode=d.E_UserCode', 'LEFT'],
            ['member_grade e', 'd.E_PointsAmount>e.E_Min and d.E_PointsAmount<e.E_Max', 'LEFT'],
            ['member_other_login f', "a.E_UserCode=f.E_UserCode and f.E_Type='wx'", 'LEFT'],
        ];
        $re['data'] = $mem->alias('a')->where($where)->join($join)->field($field)->find();
//        dump( $re['data']);

        end:
        return $this->ajaxreturn($re);

    }

    /**
     * 用户详细信息
     */
    public function getinfo()
    {
        $re['msg'] = '成功！';
        $re['code'] = 1000;
        $re['data'] = array();

        $mem = new MemberModel();
        $where = ['E_UserCode'=>session('usercode')];
        $field = 'E_Mobile,E_Name,E_HeadImg,E_IsInvestor,E_IsIdentityCard,E_Email';
        $re['data'] = $mem->where($where)->field($field)->find();

        return $this->ajaxreturn($re);
    }

    /**
     * 修改昵称
     */
    public function updateName()
    {
        $re['msg'] = '成功！';
        $re['code'] = 1000;
        $name = $this->request->param('name');
        $oldname = $this->request->param('oldname');

        if (empty($name)){
            $re['msg'] = '新昵称为空！';
            $re['code'] = 2303;
            goto end;
        }
        if ($name != $oldname) {
            $mem = new  MemberModel();

            $result=$mem->isUpdate(true)->save(['E_Name' => $name],['E_UserCode' => session('usercode')]);
            if ($result===false){
                $re['msg'] = '修改失败！';
                $re['code'] = 2304;
                goto end;
            }
        } else {
            $re['msg'] = '未作修改！';
            $re['code'] = 2305;
            goto end;
        }
        end:
        return $this->ajaxreturn($re);
    }

    /**
     * 更新密码
     */
    public function updatePWD()
    {
        $re['msg'] = '成功！';
        $re['code'] = 1000;
        $oldpwd = $this->request->param('oldpwd');
        $newpwd = $this->request->param('newpwd');
        $againpwd = $this->request->param('againpwd');

        if (empty($oldpwd) ) {
            $re['msg'] = '请输入原密码！';
            $re['code'] = 2401;
            goto end;
        }
        if (empty($newpwd)){
            $re['msg'] = '新密码为空！';
            $re['code'] = 2402;
            goto end;
        }
        if ($newpwd != $againpwd ) {
            $re['msg'] = '两次输入的密码不一致！';
            $re['code'] = 2403;
            goto end;
        }
        if ($oldpwd == $newpwd ) {
            $re['msg'] = '新密码与旧密码相同！';
            $re['code'] = 2404;
            goto end;
        }
        $mem = new MemberModel();
        $where = ['E_UserCode' => session('usercode')];
        $findpwd = $mem->where($where)->value('E_PWDMD5');
        if ($findpwd != cmf_encryption($oldpwd)) {
            $re['msg'] = '原密码不正确！';
            $re['code'] = 2405;
            goto end;
        }
        $result=$mem->save(['E_PWDMD5' => cmf_encryption($newpwd)],$where);
        if ($result===false){
            $re['msg'] = '修改失败！';
            $re['code'] = 2406;
            goto end;
        }
        end:
        return $this->ajaxreturn($re);
    }

    /**
     * 实名制
     */
    public function realName()
    {
        $re['msg'] = '成功！';
        $re['code'] = 1000;
        $truename = $this->request->param('name');
        $idcardnumber = $this->request->param('cardnumber');

        if (empty($truename) || !preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$truename)) {
            $re['msg'] = '真实姓名不合法！';
            $re['code'] = 2503;
            goto  end;
        }
//        第一、二位表示省
//        第三、四位表示市01-20，51-70表示省直辖市；21-50表示地区（自治州、盟）。
//    第五、六位表示县（市辖区、县级市、旗）。01-18表示市辖区或地区（自治州、盟）辖县级市；21-80表示县（旗）；81-99表示省直辖县级市。
//    生日期码第七位到第十四位
//    顺序码第十五位到十七位
//    校验码
//        dump(preg_match('/^[1-8][1-9][1-7][0-9]\d{2}[1-9]\d{3}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])\d{3}[0-9Xx]$/',$idcardnumber));die;
        if (empty($idcardnumber) || !preg_match('/^[1-8][1-9][1-7][0-9]\d{2}[1-9]\d{3}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])\d{3}[0-9Xx]$/',$idcardnumber)) {
            $re['msg'] = '身份证号码不合法！';
            $re['code'] = 2504;
            goto  end;
        }
//        身份证与真实姓名合法性验证（需接口）
//        $juhe = new juhe();
//        $res=$juhe->ckeckIDcard($idcardnumber, $truename);
//        if ($res['error_code']==0){
//                if ($res['result']['res']==1){
//                    $re['msg'] = '身份证和姓名不一致！';
//                    $re['code'] = 2505;
//                    goto  end;
//                }
//        }else{
//            $re['msg'] = '验证失败！';
//            $re['code'] = 2506;
//            goto  end;
//        }
//        $res = $this->CheckNameAndIDCard($truename, $idcardnumber);
//        if ($res != 1000) {
//            $re = $res;
//            goto  end;
//        }
        //入库
        $mem = new MemberModel();
        $where = ['E_UserCode'=>session('usercode')];
        $data = [
            'E_IdentityCard'=> $idcardnumber,
            'E_TrueName'=> $truename
        ];
//        dump($where);
//        dump($data);die;
        $result=$mem->save($data,$where);
        if ($result===false){
            $re['msg'] = '实名制失败！';
            $re['code'] = 2507;
            goto end;
        }
        end:
        return $this->ajaxreturn($re);
    }

    /**
     * 绑定邮箱
     */
    public function updateEmail()
    {

        $re['msg'] = '成功！';
        $re['code'] = 1000;

        $mail = $this->request->param('email');

        if (empty($mail) || !preg_match('/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-_.])+[a-zA-Z0-9]{2,4}$/', $mail)) {

            $re['msg'] = '邮箱格式不正确！';
            $re['code'] = 2601;
            goto end;
        }

        $mem = new MemberModel();
        $where = ['E_UserCode' => session('usercode')];
        $data = ['E_Email' => $mail];
        $result=$mem->save($data,$where);
        if ($result===false){
            $re['msg'] = '修改邮箱失败！';
            $re['code'] = 2502;
            goto end;
        }
        end:
        return $this->ajaxreturn($re);

    }
}