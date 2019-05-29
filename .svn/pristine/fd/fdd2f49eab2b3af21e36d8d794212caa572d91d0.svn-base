<?php
// +---------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +---------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +---------------------------------------------------------------------
namespace cmf\lib;

use FontLib\Table\Type\head;
use think\Db;

/**
 * ThinkCMF微信授权
 */
class wx
{

    //默认配置
    protected $_config = [];

    public function __construct()
    {

    }

   public function getOpenID(){
        if ($_GET['code']){
            $url='https://api.weixin.qq.com/sns/oauth2/access_token?';
            $data['appid']=config('appid');
            $data['secret']= config('secret');
            $data['code']=$_GET['code'];
            $data['grant_type']='authorization_code';
            $url=$url.$this->urlparam($data);
            $result=cmf_curl_get($url);
            if ($result['errcode']==0){
                $url='https://api.weixin.qq.com/sns/userinfo?';
                $data['access_token']=$result['access_token'];
                $data['openid']=$result['openid'];
                $data['lang']='zh_CN';
                $url=$url.$this->urlparam($data);
                $result=cmf_curl_get($url);
                if ($result['errcode']==0){
                    return $result;
                }
            }
        }else{
            $url='https://open.weixin.qq.com/connect/oauth2/authorize?';
            $data['appid']=config('appid');
            $data['redirect_uri']=urlencode('') ;
            $data['response_type']='code';
            $data['scope']='SCOPE';
            $data['state']='STATE';
            $url=$url.$this->urlparam($data).'#wechat_redirect';
            http_head($url);
            echo "<script>window.location.href=$url;</script>";
            exit();
        }


   }
    public function urlparam($array){
        $param='';
        if(is_array($array)){
            foreach ($array as $k=>$v){
                if ($k!='sign'){
                    $param.=$k.'='.$v.'&';
                }
                $param=trim($param,'&');
            }
        }
        return $param;
    }
}
