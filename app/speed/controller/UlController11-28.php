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
use cmf\lib\wx\Wx;
use think\Db;
use think\Exception;
use think\Validate;
use cmf\controller\HomeBaseController;


class UlController extends ApiController
{

    /**
     * 更新密码
     * @param string $mobile
     * @param string $pwd
     * @param string $code
     * @param string $type
     * @return array
     */
    public function Login()
    {
        header("Access-Control-Allow-Origin", "*");
        if ($this->request->isAjax()) {

        }
        $re['msg'] = '登录成功';
        $re['code'] = '1000';
        $re['data'] = array();

        $mobile = $this->request->param('mobile') ? $this->request->param('mobile') : '';
        $pwd = $this->request->param('pwd') ? $this->request->param('pwd') : '';
        $code = $this->request->param('code') ? $this->request->param('code') : '';
        $type = $this->request->param('type') ? $this->request->param('type') : 1;
        $url = $this->request->param('url') ? $this->request->param('url') : 1;

        if (!preg_match('/^1[3,9]\d{9}$/', $mobile)) {
            $re['msg'] = '请传入正确的手机号';
            $re['code'] = '1101';
            goto end;
        }
        $m = new MemberModel();
        if ($type == '1') {
            //验证码登录
            if (!preg_match('/^\d{4}$/', $code)) {
                $re['msg'] = '请传入正确验证码！';
                $re['code'] = '1102';
                goto end;
            }
            $result = $this->checkedmobilecode($mobile, $code);
            if ($result['code'] != 1000) {
                $re = $result;
                goto  end;
            }
            $res = $m->where(['E_Mobile' => $mobile])->find();
        } elseif ($type == '2') {

            if (empty($pwd)) {
                $re['msg'] = '请传入密码';
                $re['code'] = '1103';
                goto end;
            }
            $res = $m->where(['E_Mobile' => $mobile])->find();
            if (cmf_encryption($pwd) != $res['E_PWDMD5']) {
                $re['msg'] = '密码不正确';
                $re['code'] = '1104';
                goto end;
            }
        } else {
            $re['msg'] = '非法操作';
            $re['code'] = '1105';
            goto  end;
        }
        $token = cmf_token($res['E_UserCode'], 'ENCODE', 'qxh');
        $re['data']['usercode'] = $res['E_UserCode'];;

        session('usercode', $res['E_UserCode']);
        session('username', $res['E_Name']);
        setcookie('qxhusername',$token , time() + 7200,'/');
        $this->addlog($res['E_UserCode'],'登录',array('mobile'=>$mobile),get_client_ip(0,true),1);

        end:
        $this->ajaxreturn($re);
    }

//退出登录
    public function logout()
    {
        session('usercode', '');
        session('username', '');
        setcookie('qxhusername', '', time() + 7200);
        unset($_COOKIE);
        cookie(null);
        $this->ajaxreturn(['msg' => '退出成功！', 'code' => 0]);
    }

//微信授权
    public function wx()
    {
        $wx = new Wx();
        $wx->getcode();
    }

//授权回调
    public function welogincallback()
    {
        $re['msg'] = '登录成功';
        $re['code'] = '0';
        $param = $this->request->param();
        $wx = new Wx();
        $res = $wx->getuserinfo($param);
//        $res['openid'] = '7EDE8Dg4F6469416476410F';
//        $res['nickname'] = '00';
//        $res['sex'] = '1';
//        $res['province'] = '广西';
//        $res['city'] = '广州';
//        $res['country'] = '中国';
//        $res['headimgurl'] = "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46";
//        $res['unionid'] = 'FCC5C85BED6E6ABC35419FE368A00727';
        if (empty($res)) {
            $re['msg'] = '授权失败';
            $re['code'] = '1';
            goto end;
        }
        $where = ['E_OpenID' => $res['openid'], 'E_Type' => 'wx'];
        $otherlogin = Db::name('member_other_login');
        $find = $otherlogin->alias('a')->where($where)->field('E_HeadImg,E_Nickname,E_Sex,E_Country,E_Province,E_City,E_UserCode')->find();
        if (!$find) {
            $data['E_HeadImg'] = $res['headimgurl'];
            $data['E_Province'] = $res['province'];
            $data['E_Nickname'] = $res['nickname'];
            $data['E_Country'] = $res['country'];
            $data['E_OpenID'] = $res['openid'];
            $data['E_UID'] = $res['unionid'];
            $data['E_City'] = $res['city'];
            $data['E_CreateTime'] = time();
            $data['E_Sex'] = $res['sex'];
            $data['E_Type'] = 'wx';
            $otherlogin->insert($data);
            if (!$otherlogin->getLastInsID()) {
                $re['msg'] = '授权失败';
                $re['code'] = '1';
                goto end;
            }
        }
        if ($find['E_UserCode']) {
            $m = new MemberModel();
            $info = $m->where(['E_UserCode' => $find['E_UserCode']])->find();
            $token = cmf_token($info['E_UserCode'], 'ENCODE', 'qxh');
            $re['data']['usercode'] = $info['E_UserCode'];;
            $this->addlog($find['E_UserCode'],'微信授权登录',array('openid'=>$res['openid']),get_client_ip(0,true),1);
            session('usercode', $res['E_UserCode']);
            session('username', $res['E_Name']);
            setcookie('qxhusername',$token , time() + 7200);
        } else {
            $re['msg'] = '授权成功！';
            $re['code'] = '2';
            $re['data'] = ['openid' => $res['openid']];
            goto end;
        }
        end:
        return $re;
    }

    /**
     * 更新密码
     * @param string $mobile
     * @param string $pwd
     * @param string $code
     * @return array
     */
    public function forgetpwd()
    {
        $re['msg'] = '密码更新成功！';
        $re['code'] = '1000';
        $re['data'] = array();

        $mobile = $this->request->param('mobile') ? $this->request->param('mobile') : '';
        $pwd = $this->request->param('pwd') ? $this->request->param('pwd') : '';
        $code = $this->request->param('code') ? $this->request->param('code') : '';
        if (!preg_match('/^1[3,9]\d{9}$/', $mobile)) {
            $re['msg'] = '请传入正确手机号码！';
            $re['code'] = '1201';
            goto end;
        }
        if (!preg_match('/^\d{4}$/', $code)) {
            $re['msg'] = '请传入正确验证码！';
            $re['code'] = '1202';
            goto end;
        }

        $pwdlenth = strlen($pwd);
        if (empty($pwd) || $pwdlenth < 6 || $pwdlenth > 12) {
            $re['msg'] = '密码格不正确！';
            $re['code'] = '1203';
            goto  end;
        }
        $result = $this->checkedmobilecode($mobile, $code);
        if ($result['code'] != 1000) {
            $re = $result;
            goto end;
        } else {
            $m = new MemberModel();
            $data['E_PWDMD5'] = cmf_encryption($pwd);
            $where = ['E_Mobile' => $mobile];
            $result = Db::name('member')->where($where)->update($data);
            if ($result === false) {
                $re['msg'] = '更新密码失败！';
                $re['code'] = '1204';
                goto  end;
            } else {
                $info = $m->where(['E_Mobile' => $mobile])->find();
                $token = cmf_token($info['E_UserCode'], 'ENCODE', 'qxh');

                $re['data']['usercode'] = $info['E_UserCode'];
                session('usercode', $info['E_UserCode']);
                session('username', $info['E_Name']);
                setcookie('qxhusername', $token, time() + 7200);
            }
        }
        end:
        $this->ajaxreturn($re);
    }

    /**
     * 更新密码
     * @param string $mobile
     * @param string $pwd
     * @param string $code
     * @param string $openid
     * @param string $invite
     * @return array
     */
    public function register()
    {
        $re['msg'] = '注册成功！';
        $re['code'] = '1000';
        $mobile = $this->request->param('mobile') ? $this->request->param('mobile') : '';
        $pwd = $this->request->param('pwd') ? $this->request->param('pwd') : '';
        $code = $this->request->param('code') ? $this->request->param('code') : '';
        $type = $this->request->param('type') ? $this->request->param('type') : '';
        $openid = $this->request->param('openid') ? $this->request->param('openid') : '';

        if (!preg_match('/^1[3,9]\d{9}$/', $mobile)) {
            $re['msg'] = '请传入正确手机号码！';
            $re['code'] = '1301';
            goto end;
        }
        if (!preg_match('/^\d{4}$/', $code)) {
            $re['msg'] = '请传入正确验证码！';
            $re['code'] = '1302';
            goto end;
        }

        $pwdlenth = strlen($pwd);

        if ($pwdlenth < 6 || $pwdlenth > 12) {
            $re['msg'] = '密码长度长度为6到12位！';
            $re['code'] = '1303';
            goto end;
        }
        $m = new MemberModel();
        $find = $m->where(['E_Mobile' => $mobile])->value('id');
        if (!empty($find)) {
            $re['msg'] = '用户已存在！';
            $re['code'] = '1304';
            goto end;
        }
        $result = $this->checkedmobilecode($mobile, $code);

        if ($result['code'] != 1000) {
            $re = $result;
            goto  end;
        }

        if (!empty($openid)) {
            $other = Db::name('member_other_login')->where(['E_OpenID' => $openid])->field('id,E_UserCode')->find();
            if (!$other) {
                $re['msg'] = '微信信息不存在！';
                $re['code'] = '1305';
                goto end;
            }
        }

        $usercode = $this->builname();
        $data['E_Mobile'] = $mobile;
        $data['E_UserCode'] = $usercode;
        $data['E_PWDMD5'] = cmf_encryption($pwd);
        $data['E_Name'] = $mobile;
        $m->insert($data);
        if ($openid) {
            $odata['E_UserCode'] = $usercode;
            $where = ['E_OpenID' => $openid];
            Db::name('member_other_login')->where($where)->update($odata);
        }

        $token = cmf_token($usercode.'-'.time(), 'ENCODE', 'qxh');
        $re['data']['usercode'] =$usercode ;
        session('usercode', $usercode);
        session('username', $mobile);
        setcookie('qxhusername', $token, time() + 7200);
        end:
        $this->ajaxreturn($re);

    }


    /**
     * 生成唯一的编号
     * @return array
     */
    public function builname()
    {
        $m = new MemberModel();
        $un = $m->getUM();
        $code = 'qxh' . $un;
        $res = $m->where(['E_UserCode' => $code])->count();
        if ($res > 0) {
            $this->builname();
        } else {
            return $code;
        }
    }

//发送验证码
    public function sendcode()
    {
        $re['msg'] = "发送成功！";
        $re['code'] = '1000';
//        goto end;
        $number = $this->request->param('number') ? $this->request->param('number') : '';
        $type = $this->request->param('type') ? $this->request->param('type') : 1;
        $mode = $this->request->param('mode') ? $this->request->param('mode') : '1';
        $types = [1, 2, 3];
        if (!in_array($type, $types)) {
            $re['msg'] = "非法操作！";
            $re['code'] = '1401';
            goto end;
        }
        if ($mode == 1) {
//            手机验证码
            if (!preg_match('/^1[3,9]\d{9}$/', $number)) {
                $re['msg'] = "请传入正确的手机号！";
                $re['code'] = '1402';
                goto end;
            }
            if($type==1){
               $id= Db::name('member')->where(['E_Mobile'=>$number])->value('id');
               if ($id){
                   $re['msg'] = "该手机已经注册过！";
                   $re['code'] = '1403';
                   goto end;
               }
            }


        } elseif ($mode == 2) {
            if (!preg_match('/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d][-.])+[A-Za-z\d]{2,4}$/', $number)) {
                $re['msg'] = "请传入正确的邮箱！";
                $re['code'] = '1404';
                goto end;
            }
        } else {
            $re['msg'] = "非法请求！";
            $re['code'] = '1405';
            goto end;
        }

        $smscode = Db::name('sms_code');
        $where = array('E_Number' => $number,);
        $field = 'id,E_Code,E_SendNum,E_Sendtime,E_State';
        $find = $smscode->where($where)->field($field)->find();


        $code = rand(1000, 9999);
        $num = 1;
        if ($find) {
            $config = config('SMS');
            $istoday = date('d', $find['E_Sendtime']) == date('d', time()) ? true : false;
//            今天发送过
            if ($istoday) {

                $num = $find['E_SendNum'] + 1;

                if ($find['E_SendNum'] > $config['sendnum']) {
                    $re['msg'] = "超过发送次数！";
                    $re['code'] = '1406';
                    goto end;
                }

                if (time() < ($find['E_Sendtime'] + $config['interval'])) {
                    $re['msg'] = "短信发送过于频繁！";
                    $re['code'] = '1407';
                    goto end;
                }
                if ($find['E_State'] == 0 && ($find['E_Sendtime'] + $config['effective']) > time()) {
                    $code = $find['E_Code'];
                }
            }
        }

        if ($mode == '1') {
            $returns = $this->sendmobilecode($number, $code, $type);
        } elseif ($mode == '2') {
            $returns = $this->sendemailcode($number, $code, $type);
        }

        $data['id'] = empty($find) ? '' : $find['id'];
        $data['E_Number'] = $number;
        $data['E_CreateIP'] = get_client_ip();
        $data['E_State'] = 0;
        $data['E_Code'] = $code;
        $data['E_Senddate'] = date('Y-m-d');
        $data['E_Sendtime'] = time();
        $data['E_Scene'] = $type;
        $data['E_SendNum'] = $num;
        if ($find) {
            $res = $smscode->update($data);
        } else {
            $res = $smscode->insert($data);
        }
        if ($res === false) {
            $re['msg'] = '发送失败';
            $re['code'] = '1408';
            goto end;
        }
        end:
        $this->ajaxreturn($re);
    }

    public function decodetoken()
    {
        $str = '4b3469SrCvoWX2OMH6wlCnJ5nY7O3WSDkhS+R2PFnmv2qqXHlDR0YGyOmi8';
        dump(cmf_token($str, "DECODE", 'qxh'));
    }

}