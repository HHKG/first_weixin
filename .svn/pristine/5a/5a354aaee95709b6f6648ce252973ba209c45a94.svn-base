<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;
use think\Cache;

class HotelModel extends Model
{

    public function GetCouponList(){
        $where=['E_IsDelete'=>1];
        $field='id,E_State,E_Name,E_Money,E_Type,E_Condition,E_Amount,E_Send,E_SendType,E_SendStartTime,E_SendEndTime,E_CouponImg,E_LastDay,E_CanUseMoney,E_IsSuperposition';
        return $this->where($where)->field($field)->paginate(10);
    }

}
