<?php
namespace app\shop\model;

use think\Model;

class Order extends Model
{
    //关联订单商品表
    public function  orderinfo(){
        return $this->hasMany('OrderInfo','oid','id');
    }
    //关联用户表
    public function user(){
        return $this->belongsTo('Tpuser','uid','id');
    }
    //关联用户表
    public function cate(){
        return $this->belongsTo('TpgoodsCategory','type','id');
    }

    public function getStateAttr($value){
        $arr=['-1'=>'已退款','无效订单','待付款','已付款','已发货','已完成'];
        return $arr[$value];
    }
    public function getKuaidiAttr($company,$data){
        if($data['company']){
            $arr=['ZTO'=>'中通快递','YD'=>'韵达速递','YTO'=>'圆通速递','YZPY'=>'邮政快递包裹','EMS'=>'EMS','DBL'=>'德邦快递','ZJS'=>'宅急送'];
            return $arr[$data['company']];
        }else{
            return '';
        }


    }

    public function getUptimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
}