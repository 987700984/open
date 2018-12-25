<?php
namespace app\shop\model;

use think\Model;

class Tpspec extends Model
{
    //关联商品模型表
    public function goodstype(){
        return $this->belongsTo('TpgoodsType','tid','tid');
    }

    //关联规格属性分类表
    public function spec_item(){
        return $this->hasMany('TpspecItem','sid','sid');
    }
}