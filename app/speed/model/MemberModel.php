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
namespace app\speed\model;

use think\Db;
use think\Model;

class MemberModel extends Model
{
    protected $type = [
        'more' => 'array',
    ];
    public function getUM(){
        return substr(uniqid('',true),-6);
    }
//    public function updateinfo($where,$data){
//       return $this->save($data,$where);
//    }
    public function dologin($where){
        $field='E_UserID,E_PWDMD5,E_Name,E_HeadImg,E_Email,E_Sex,E_Age';
        $this->where($where)->field($field)->find();
    }
    public function updatePWD($where,$data){
        return $this->save($data,$where);
    }
}
