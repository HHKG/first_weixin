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
namespace cmf\lib\wx;

use FontLib\Table\Type\head;
use think\Db;

/**
 * ThinkCMF微信授权
 */
class Wx
{

    //默认配置
    private static $appid='';
    private static $secret='';
    private $state='qxhweixin';


    public function __construct()
    {

    }
    public function getcode(){
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?';
        $data['appid']=self::$appid;
        $data['redirect_uri']=urlencode('localhost:84/portal/login/wxlogincallback') ;
        $data['response_type']='code';
        $data['scope']='SCOPE';
        $data['state']=base64_encode($this->state);
        $url=$url.$this->urlparam($data).'#wechat_redirect';
//        dump($url);
//        dump($data);;die;
        echo "<script>window.location.href=$url;</script>";
        exit();
    }
//    public function getuserinfo($param){
//        $msg='';
//        if (empty($param['state']) || $param['state']!= base64_decode($this->state)) goto end;
//        if (empty($param['code'])) {
//            $msg='用户禁止授权！';
//            goto end;
//        }
//        $result=$this->getaccesstoken($param['code']);
//        if (!$result){
//            $msg='获取ACCESS TOKEN失败！';
//            goto end;
//        }
//        $info=$this->getinfo($result['access_token'],$result['openid']);
//        if ($info) return $info;
//        end:
//        $this->log($msg);
//            return false;
//    }
    public function getaccesstoken($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?';
        $data['appid']=self::$appid;
        $data['secret']= self::$secret;
        $data['code']=$code;
        $data['grant_type']='authorization_code';
        $url=$url.$this->urlparam($data);
        return cmf_curl_get($url);
    }
    public function getinfo($access,$openid){
        $url='https://api.weixin.qq.com/sns/userinfo?';
        $data['access_token']=$access;
        $data['openid']=$openid;
        $data['lang']='zh_CN';
        $url=$url.$this->urlparam($data);
        return cmf_curl_get($url);
    }
    public function urlparam($array){
        $param='';
        if(is_array($array)){
            foreach ($array as $k=>$v){
                if ($k!='sign'){
                    $param.=$k.'='.$v.'&';
                }
            }
            $param=trim($param,'&');
        }
        return $param;
    }
    /**
     * GET 请求
     * @param string $url
     */
    private function http_get($url, $data = array()) {
        $oCurl = curl_init();
        if(stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        $query = http_build_query($data);
        if ('' != $query) $url .= '?' . $query;
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
        $sContent = curl_exec($oCurl);
        $httpCode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
        curl_close($oCurl);
        if(intval($httpCode) != 200) {
            $this->log($sContent);
        }
        return $sContent;
    }



    /**
     * POST 请求
     * @param string $url
     * @param array $post_data
     * @param array $header
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $post_data, $header=[], $post_file=false) {   //默认
        $oCurl = curl_init();
        if (stripos($url,"https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($post_data) || $post_file) {
            $strPOST = $post_data;
        } else {
            $strPOST =  json_encode($post_data);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $httpCode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
        curl_close($oCurl);
        if (intval($httpCode) != 200) {
            $this->log($sContent);
        }
        return $sContent;
    }
}
