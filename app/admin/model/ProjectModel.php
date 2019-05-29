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


class ProjectModel extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime='E_Create_time';
    protected $updateTime='E_Update_time';
    public static $a = 111;
    public static $Progress = array(
        '1' => '预告中',
        '2' => '预约中',
        '3' => '认购中',
        '4' => '已结束',
    );
    public static $role = array(
        '1' => '租赁',
        '2' => '自持有',
        '3' => '其他',

    );
    public static $p_type = array(
        '1' => '酒店',
        '2' => '公寓',
        '3' => '民宿',
        '4' => '共享办公',
        '5' => '休闲娱乐',
        '6' => '新零售',
        '7' => '医疗健康',
        '8' => '亲子',
        '9' => '其他',

    );

//getPostContentAttr
    public function getEIntroductionAttr($value)
    {
        return cmf_replace_content_file_url(htmlspecialchars_decode($value));
    }
    public  function setEIntroductionAttr($value){
        return htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($value), true));
    }
    public function getcode($len=20){
        $code=cmf_random_string($len);
        if ($this->where(['E_Code'=>$code])->count()){
            $this->getcode($len);
        }
        return $code;
    }
    public function addProject($data)
    {
            $data['E_Create_user']=session('name');
            $this->save($data);
        return $this->getLastInsID();
    }
    public function GetOne($code){
        return $this->where(['E_Code'=>$code])->find();
    }
    public function editProject($data,$code){
        $data['E_Update_user']=session('name');
        return $this->save($data,['E_Code'=>$code]);

    }
}