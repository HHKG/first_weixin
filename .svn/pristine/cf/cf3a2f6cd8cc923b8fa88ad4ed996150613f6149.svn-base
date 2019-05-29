<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace cmf\controller;

use think\Db;
use app\admin\model\ThemeModel;
use think\Debug;
use think\View;

class HomeBaseController extends BaseController
{
    protected $limit = 10;
    protected $http='';
    public function _initialize()
    {   $this->http=cmf_is_ssl()?'https':'http';
        $this->assign('http',$this->http);
        $pattern=$this->request->param('pattern');
        if (isset($pattern)){
            session('pattern',$pattern);
           // $this->redirect('/member');
        }
        $url=$_SERVER['REQUEST_URI'];
      //  dump(strstr($url,'pattern'));
        if (strstr($url,'pattern=')){
            $url= str_replace('&pattern=1','',$url);
            //dump($url);
            $url=  str_replace('?pattern=1','',$url);
            $url=  str_replace('&pattern=0','',$url);
            $url=  str_replace('?pattern=0','',$url);
            $this->redirect($url);
        }



       //
        // 监听home_init
        hook('home_init');
        parent::_initialize();
        $token =$this->request->param('token');
//
//        dump($this->request->param());

        if (!empty($token)){

            session('token', $token );
        }
        $this->setsign();
//        dump(session('token'));
//        $siteInfo = cmf_get_site_info();
//        View::share('site_info', $siteInfo);

    }
    private function setsign(){
        $time=time();
        $salt=config('signsalt');
        $from='web';
        $sign=md5($from.$salt.$time);
        $this->assign('time',$time);
        $this->assign('from',$from);
        $this->assign('randomstr',$sign);
    }

    protected function islogin()
    {
//        dump(cmf_url());die;
        $token = session('token');
//        dump($token);die;
        if (!empty($token)){
//            dump($token);die;
            return true ;
        }
        if (!cmf_is_wechat()){
            $url=urlencode(GetAllURL());
            $this->redirect("/login?back=$url");
        }
        $wxcode=session('wxcode');
//        if ()
        $code = $this->request->param('code')?$this->request->param('code') : '';

        if(empty($token) && empty($code)){
//            $back=urlencode('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
            $back = $this->request->param('back')?$this->request->param('back') : GetAllURL();
//            $back=GetAllURL();
            $url=$this->http.config('newinterfaceurl').'/wx_getcodeurl?callback='.$back;

            $result=cmf_curl_get($url);
            $result=json_decode($result,true);

            if (isset($result['code']) && $result['code']==1){
                $url=$result['url'];
                header("location:$url");
                exit();
            }
        }
        if (!empty($code) && empty($token)){
            session('wxcode',$code);
            $url=GetAllURL();
            $str='/code=[\w]*&/';
            $url=preg_replace($str,'',$url);
            $this->redirect($url);
            dump($url);
            die;
            $url=$this->http.config('newinterfaceurl').'/wxcallback?code='.$code;
            $result=cmf_curl_get($url);
            $result=json_decode($result,true);
            if (isset($result['code']) && $result['code']==1 ){
                if ( $result['new']==-1){
                    session('token',$result['token']);
                    session('member',$result['user']);
                    return;
                }else{
                    $backurl=GetAllURL();
//                    dump($back);die;
                    $this->redirect("/bindmobile?back=".urlencode($backurl)."&openid=".$result['openid']);
                }

            }
        }

    }


//    public function checklogin()
//    {
//        $username = session('username');
//        $usercode = session('usercode');
//        $cookiename = cookie('qxhusername');
//        $res = cmf_token($cookiename, 'DECONDE', 'qxh') == $username ? true : false;
//        if (!empty($username) && !empty($cookiename) && !empty($usercode) && $res) {
//            return true;
//        } else {
//            return false;
//        }
//    }

    public function _initializeView()
    {
        $cmfThemePath = config('cmf_theme_path');
        $cmfDefaultTheme = cmf_get_current_theme();

        $themePath = "{$cmfThemePath}{$cmfDefaultTheme}";

        $root = cmf_get_root();
        //使cdn设置生效
        $cdnSettings = cmf_get_option('cdn_settings');
        if (empty($cdnSettings['cdn_static_root'])) {
            $viewReplaceStr = [
                '__ROOT__' => $root,
                '__TMPL__' => "{$root}/{$themePath}",
                '__STATIC__' => "{$root}/static",
                '__WEB_ROOT__' => $root
            ];
        } else {
            $cdnStaticRoot = rtrim($cdnSettings['cdn_static_root'], '/');
            $viewReplaceStr = [
                '__ROOT__' => $root,
                '__TMPL__' => "{$cdnStaticRoot}/{$themePath}",
                '__STATIC__' => "{$cdnStaticRoot}/static",
                '__WEB_ROOT__' => $cdnStaticRoot
            ];
        }

        $viewReplaceStr = array_merge(config('view_replace_str'), $viewReplaceStr);
        config('template.view_base', "{$themePath}/");
        config('view_replace_str', $viewReplaceStr);

        $themeErrorTmpl = "{$themePath}/error.html";
        if (file_exists_case($themeErrorTmpl)) {
            config('dispatch_error_tmpl', $themeErrorTmpl);
        }

        $themeSuccessTmpl = "{$themePath}/success.html";
        if (file_exists_case($themeSuccessTmpl)) {
            config('dispatch_success_tmpl', $themeSuccessTmpl);
        }


    }


    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $template = $this->parseTemplate($template);
        $more = $this->getThemeFileMore($template);
        $this->assign('theme_vars', $more['vars']);
        $this->assign('theme_widgets', $more['widgets']);
        $content = parent::fetch($template, $vars, $replace, $config);

        $designingTheme = session('admin_designing_theme');

        if ($designingTheme) {
            $app = $this->request->module();
            $controller = $this->request->controller();
            $action = $this->request->action();

            $output = <<<hello
<script>
var _themeDesign=true;
var _themeTest="test";
var _app='{$app}';
var _controller='{$controller}';
var _action='{$action}';
var _themeFile='{$more['file']}';
parent.simulatorRefresh();
</script>
hello;

            $pos = strripos($content, '</body>');
            if (false !== $pos) {
                $content = substr($content, 0, $pos) . $output . substr($content, $pos);
            } else {
                $content = $content . $output;
            }
        }


        return $content;
    }

    /**
     * 自动定位模板文件
     * @access private
     * @param string $template 模板文件规则
     * @return string
     */
    private function parseTemplate($template)
    {
        // 分析模板文件规则
        $request = $this->request;
        // 获取视图根目录
        if (strpos($template, '@')) {
            // 跨模块调用
            list($module, $template) = explode('@', $template);
        }

        $viewBase = config('template.view_base');

        if ($viewBase) {
            // 基础视图目录
            $module = isset($module) ? $module : $request->module();
            $path = $viewBase . ($module ? $module . DS : '');
        } else {
            $path = isset($module) ? APP_PATH . $module . DS . 'view' . DS : config('template.view_path');
        }

        $depr = config('template.view_depr');
        if (0 !== strpos($template, '/')) {
            $template = str_replace(['/', ':'], $depr, $template);
            $controller = cmf_parse_name($request->controller());
            if ($controller) {
                if ('' == $template) {
                    // 如果模板文件名为空 按照默认规则定位
                    $template = str_replace('.', DS, $controller) . $depr . cmf_parse_name($request->action(true));
                } elseif (false === strpos($template, $depr)) {
                    $template = str_replace('.', DS, $controller) . $depr . $template;
                }
            }
        } else {
            $template = str_replace(['/', ':'], $depr, substr($template, 1));
        }
        return $path . ltrim($template, '/') . '.' . ltrim(config('template.view_suffix'), '.');
    }

    /**
     * 获取模板文件变量
     * @param string $file
     * @param string $theme
     * @return array
     */
    private function getThemeFileMore($file, $theme = "")
    {

//        //TODO 增加缓存
//        $theme = empty($theme) ? cmf_get_current_theme() : $theme;
//
//        // 调试模式下自动更新模板
//        if (APP_DEBUG) {
//            $themeModel = new ThemeModel();
//            $themeModel->updateTheme($theme);
//        }
//
//        $themePath = config('cmf_theme_path');
//        $file = str_replace('\\', '/', $file);
//        $file = str_replace('//', '/', $file);
//        $themeFile = str_replace(['.html', '.php', $themePath . $theme . "/"], '', $file);
//
//        $files = Db::name('theme_file')->field('more')->where(['theme' => $theme])->where(function ($query) use ($themeFile) {
//            $query->where(['is_public' => 1])->whereOr(['file' => $themeFile]);
//        })->select();
//
//        $vars = [];
//        $widgets = [];
//        foreach ($files as $file) {
//            $oldMore = json_decode($file['more'], true);
//            if (!empty($oldMore['vars'])) {
//                foreach ($oldMore['vars'] as $varName => $var) {
//                    $vars[$varName] = $var['value'];
//                }
//            }
//
//            if (!empty($oldMore['widgets'])) {
//                foreach ($oldMore['widgets'] as $widgetName => $widget) {
//
//                    $widgetVars = [];
//                    if (!empty($widget['vars'])) {
//                        foreach ($widget['vars'] as $varName => $var) {
//                            $widgetVars[$varName] = $var['value'];
//                        }
//                    }
//
//                    $widget['vars'] = $widgetVars;
//                    $widgets[$widgetName] = $widget;
//                }
//            }
//        }
//
//        return ['vars' => $vars, 'widgets' => $widgets, 'file' => $themeFile];
    }

    public function checkUserLogin()
    {
        $userId = cmf_get_current_user_id();
        if (empty($userId)) {
            if ($this->request->isAjax()) {
                $this->error("您尚未登录", cmf_url("user/Login/index"));
            } else {
                $this->redirect(cmf_url("user/Login/index"));
            }
        }
    }

    public function getConfiginfo($type)
    {
        if (!empty($type)) {
            $config = Db::name('config')->where(['E_Type' => $type])->value('E_Config');
            return @unserialize($config);
        }
    }

    /**
     * 验证手机验证码
     * @param string $mobile
     * @param string $code
     * @return array
     */

    public function checkedmobilecode($mobile, $code)
    {
        $re["msg"] = "验证成功";
        $re ["code"] = "1000";

//        if (!preg_match('/^\d{4}$/', $code)) {
//            $re['msg'] = '请传入正确验证码！';
//            $re['code'] = '9001';
//            goto end;
//        }
//        if (!preg_match('/^1[3,9]\d{9}$/', $mobile)) {
//            $re['msg'] = '请传入正确手机号码！';
//            $re['code'] = '9002';
//            goto end;
//        }

        $find = Db::name('sms_code')->where(['E_Number' => $mobile,])->field('id,E_Code,E_Sendtime,E_State')->find();
        if ($find['E_Code'] != $code) {
            $re["msg"] = "验证码不正确！";
            $re ["code"] = "9001";
            goto end;
        }
        if ($find['E_State'] != 0) {
            $re["msg"] = "验证码已经使用过！";
            $re ["code"] = "9002";
            goto  end;
        }
        if (time() > ($find['E_Sendtime'] + config('SMS.effective'))) {
            $re["msg"] = "验证码已经过期！";
            $re ["code"] = "9003";
            goto end;
        }
        $this->checksmscodestate($find['id']);

        end:
        return $re;
    }

    /**
     * 更改验证码状态
     * @param string $id
     * @return array
     */
    public function checksmscodestate($id)
    {
        return Db::name('sms_code')->where(['id' => $id])->update(['E_State' => 1]);
    }

    /**
     * 发送手机验证码
     * @param string $moblie
     * @param string $code
     * @param string $type
     * @return array
     */
    public function sendmobilecode($moblie, $code, $type = 1)
    {
        $config = config('mobile');
        $msg = str_replace('####', $code, $config['template']);
        $result = array();
        $res = $this->smslog($result, $msg, $type);
        return true;
        //发送日志

    }

    /**
     * 发送邮件验证码
     * @param string $moblie
     * @param string $code
     * @param string $type
     * @return array
     */
    public function sendemailcode($moblie, $code, $type = 1)
    {
        $config = config('email');
        $msg = str_replace('####', $code, $config['template']);
        $result = array();
        $res = $this->smslog($result, $msg, $type);
        return true;
        //发送日志

    }

    /**
     * 写入发送验证码记录
     * @param array $result
     * @param string $type
     * @return array
     */
    public function smslog($result = array(), $msg, $type)
    {
        $data['E_CreateDate'] = time();
        $data['E_CreateIP'] = get_client_ip();
        $data['E_State'] = '';
        $data['E_Number'] = '';
        $data['E_Msg'] = $msg;
        $data['E_Type'] = $type;
        $data['E_Info'] = json_encode($result);
        return Db::name('sms_log')->insert($data);
    }

    public function weixin()
    {

    }

    public function checkpwd($param)
    {
        $re['msg'] = '验证成功！';
        $re['code'] = 0;

        if (!preg_match('/^1[3,9]\d{9}$/', $param['mobile']) || !$param['pwd']) {
            $re['msg'] = '请传入正确的手机号或密码！';
            $re['code'] = 1;
            goto  end;
        }

        $mem = Db::name('member');
        $where = ['E_Mobile' => $param['mobile'], 'E_State' => 1];
        $field = 'id,E_PWDMD5,E_Name,E_Sex,E_UserCode,E_Mobile';
        $find = $mem->where($where)->field($field)->find();
        if (!$find) {
            $re['msg'] = '用户不存在！';
            $re['code'] = 1;
            goto end;
        }
        if (cmf_encryption($param['pwd']) != $find['E_PWDMD5']) {
            $re['msg'] = '密码不正确！';
            $re['code'] = 1;
            goto end;
        }
        $re['data'] = $find;
        end:
        return $re;
    }

    public function getusercode()
    {
        $prefix = 'QXH';
        $date = date('ymd');
        $rand = cmf_random_string(5);
        return $prefix . $date . $rand;
    }

    protected function ajaxreturn($data, $type = '', $json_option = 0)
    {
        if (empty($type)) $type = config('default_ajax_return');
        switch (strtoupper($type)) {
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default     :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
        }
    }
    /**
     *  创建静态页面
     * @access   protected
     * @cacheKey 生成的静态文件名称
     * @return string
     */
    public function creathtml($cachekey,$route=''){
        $route = $route ? $route : '';
        ob_start();
        ob_implicit_flush(0);
        return $this->fetch($route);
        $content = ob_get_clean();
        file_put_contents(APP_PATH . '/' .$cachekey. '.html', $content);
        echo $content;
//        $route=$route?$route:'';
//
//        ob_start();
//        ob_implicit_flush(0);
//        $this->fetch($route);
//         $content=ob_get_clean();
//            dump($content);
//        file_put_contents(APP_PATH.'/1.html',$content);
//        echo $content;
    }
}