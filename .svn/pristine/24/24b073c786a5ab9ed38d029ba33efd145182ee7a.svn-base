<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 15:43
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class ProjectplanModel extends Model
{
    protected $name='project_plan';
    protected $autoWriteTimestamp=true;
    protected $createTime='E_Create_time';
    protected $updateTime='E_Update_time';

    public function getEIntroductionAttr($value)
    {
        return cmf_replace_content_file_url(htmlspecialchars_decode($value));
    }
    public  function setEIntroductionAttr($value){
        return htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($value),true));
    }
    public function getcode($len=20){
        $code=cmf_random_string($len);
        if ($this->where(['E_Code'=>$code])->count()){
            $this->getcode($len);
        }
        return $code;
    }
    public function addPlan($param){
        $param['E_Create_user']=session('name');
       return  $this->save($param);
    }
    public function GetPlan($code){
        return $this->where(['E_Code'=>$code])->find();
    }
    public function editPlan($param,$code){
        $param['E_Update_user']=session('name');
        return  $this->save($param,['E_Code'=>$code]);
    }
}