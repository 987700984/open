<?php
namespace app\shop\model;

use think\Model;

class TpspecItem extends Model
{
    //关联属性分类表
    public function spec(){
        return $this->belongsTo('Tpspec','sid','sid');
    }
}