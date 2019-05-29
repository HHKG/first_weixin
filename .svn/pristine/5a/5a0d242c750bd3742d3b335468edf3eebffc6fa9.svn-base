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

class TextController extends AdminBaseController
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

    public function sendmsg(){
        $access_token=$this->GetAccessToken();
//        dump($access_token);die;
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
        $info['touser']='oPmFPv37zpwUNrQ3Lh7B_TeFeyd0';
        $info['template_id']='jEj7c_Qb7AdLgRLDFb0x5NWK2lOWOKjVvvx8Y7_PhAY';
//        $info['url']='http://weixin.qq.com/download';
        $info['data']['name']['value']='黄先生';//方案名
        $info['data']['name']['color']='#173177';
        $info['data']['remark']['value']='';
        $info['data']['remark']['color']='#173177';
//        $data=json_encode($info)
        $post_data =json_encode($info);
//        dump($post_data);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url); //设置发送数据的网址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设置有返回值，0，直接显示
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0); //禁用证书验证
        curl_setopt($ch, CURLOPT_POST, 1);
        //post方法请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//post请求发送的数据包
        //接收执行返回的数据
        $data = curl_exec($ch);
        //关闭句柄
        curl_close($ch);
        $data = json_decode($data,true); //将json数据转成数组
        dump($data) ;

    }
}
