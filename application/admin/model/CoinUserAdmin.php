<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 18:05
 */
namespace app\admin\model;

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
        $status = [0=>'暂未开始',1=>'发放完毕',2=>'发放中',3=>'停止发放'];
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
    /**
     * 添加发币记录信息
     * @param $param
     */
    public function insertCoin($param)
    {
        try{

            $result =  $this->validate( [
                'amount'  => '>:0|require|number',
                'timing'   => '>:0|number',

            ],
                [
                    'amount.require' => '金额必须',
                    'amount.number'     => '金额格式不对',
                    'amount'        => '金额必须大于0',
                    'timing.number'     => '天数格式不对',
                    'timing'        => '天数大于0',
                ])->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '发币成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}