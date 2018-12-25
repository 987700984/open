<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 18:05
 */
namespace app\api\model;

use think\Model;

class CoinUserAdmin extends Model
{

    /**
     * 与user表关联
     * @param $param
     */
    public function user()
    {
        return $this->belongsTo('UserAgent','uid','id');
    }

    /**
     * 与cointype表关联
     * @param $param
     */
    public function typ()
    {
        return $this->belongsTo('CoinType','typeid','id');
    }

    public function getStatusAttr($value)
    {
        $status = [0=>'发放中',1=>'停止发放'];
        return $status[$value];
    }
    public function getIsInstantAttr($value)
    {
        $status = [1=>'即时发放',2=>'定时发放'];
        return $status[$value];
    }

    /**
     * 与Tpsoretype表关联
     * @param $param
     */
    public function tpi()
    {
        return $this->belongsTo('Tpsoretype','sid','id');
    }

}