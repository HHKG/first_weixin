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
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ConfigModel;

class WxCustomController extends AdminBaseController
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
     * 后台首页
     */

    public function index()
    {
        $access_token=$this->GetAccessToken();
        $url=' https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.$access_token;
    }

    public function type_index()
    {
        $coupon=new CouponModel();

        $coupons=$coupon->GetAllCoupon();
//        dump($coupons);die;
        $this->assign('coupons', $coupons);
        return $this->fetch();
    }

    private function GetArrConfig($con, $type)
    {
        $newcon = null;
        $con = @unserialize($con);
        // dump($con);
        if (is_array($con)) {
            return $con;
        } else {
            if ($type == 'MobileSms') {
                $newcon = array(
                    "PAYTXT" => array(
                        "name" => "说明",
                        "val" => "手机验证码配置",
                        "text" => "",
                        "itype" => "0"
                    ),

                    "AMOUNT" => array(
                        "name" => "最大发送次数",
                        "val" => "",
                        "text" => "",
                        "itype" => "0"
                    ),
                    "ACCOUNT SID" => array(
                        "name" => "ACCOUNT SID",
                        "val" => "",
                        "text" => "主帐号,对应开发者官网主账号下的ACCOUNT SID",
                        "itype" => "0"
                    ),
                    "AUTH TOKEN" => array(
                        "name" => "AUTH TOKEN",
                        "val" => "",
                        "text" => "主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN",
                        "itype" => "0"
                    ),
                    "APP ID" => array(
                        "name" => "APP ID",
                        "val" => "",
                        "text" => "应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID",
                        "itype" => "0"
                    ),
                    "CODE" => array(
                        "name" => "code",
                        "val" => "",
                        "text" => "自定义code",
                        "itype" => "0"
                    ),
                    "TEMPLATE" => array(
                        "name" => "短信模板",
                        "val" => "",
                        "text" => "变化的信息请用####表示",
                        "itype" => "0"
                    ),
                    "DAY" => array(
                        "name" => "发送间隔",
                        "val" => "",
                        "text" => "短信验证码发送间隔，单位秒",
                        "itype" => "0"
                    ),
                    "EFFECTIVE" => array(
                        "name" => "有效时间",
                        "val" => "",
                        "text" => "短信验证码过期时间，单位秒",
                        "itype" => "0"
                    ),
                );
            }


            return $newcon;
        }
    }
    public function DataSave(){
        $type =$this->request->param('txttype');
        $where=array('E_Type'=>$type);
        $config=new ConfigModel();
        $find=$config->where($where)->find();

        $ConS = $this->GetArrConfig ( $find ["E_Config"], $type );


        $data['E_Name'] =$this->request->param('txtname');
        $data['E_UpdateUser'] =session('name');
        $data['E_UpdateDate'] =time();


        $wheresave ['E_Type'] = $type;
        $pay_key = $this->request->param('pay_key/a');
        $pay_type = $this->request->param('pay_type/a');
        $pay_text = $this->request->param('pay_text/a');
//        dump($pay_type);DIE;
        $pay_val = $_POST ['pay_val'];
        foreach ( $pay_key as $key => $val ) {

            if ($pay_type [$key] == 1) {
                $tmp_name = $_FILES ["UPfile{$key}"] ["tmp_name"];
                $filename = $_FILES ["UPfile{$key}"] ['name'];
                $cer = file_get_contents ( $tmp_name );
                $cer = mb_convert_encoding ( $cer, 'utf-8', 'gbk' );
                if (! $cer || $cer == "") {
                    $cer = $ConS [$key] ["val"];
                }

                $pay_key [$key] = array (
                    "name" => $ConS [$key] ['name'],
                    "val" => $cer,
                    "itype" => $pay_type [$key],
                    "text" => $pay_text [$key]
                );
            } else {
                $pay_key [$key] = array (
                    "name" => $ConS [$key] ['name'],
                    "val" => $pay_val [$key],
                    "itype" => $pay_type [$key],
                    "text" => $pay_text [$key]
                );
            }
        }

        $pay_key = serialize ( $pay_key );
        $data['E_Config'] =$pay_key;
//        dump($data);
//        dump($wheresave);
//        die;
        $config->where($wheresave)->update($data);
        $this->success('编辑成功！','config/index');
    }
}
