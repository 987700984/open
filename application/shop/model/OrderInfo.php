<?php
namespace app\shop\model;

use think\Model;

class OrderInfo extends Model
{
    //关联订单表
    public function  order(){
        return $this->belongsTo('Order','oid','id');
    }

    //关联商品表
    public function goods(){
        return $this->belongsTo('Tpgoods','goodsid','goodsid');
    }

    public function getKeyAttr($value){
        if($value){
            $key=explode(',',$value);
            $list='';
            foreach ($key as $k=>$v){
                $a=TpspecItem::get($v);
                if($k!=0){
                    $list.=','.$a['item_name'];
                }else{
                    $list.=$a['item_name'];
                }
            }
            return $list;
        }else{
            return $value;
        }

    }
}