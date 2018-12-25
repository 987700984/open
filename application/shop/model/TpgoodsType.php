<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\shop\model;

use think\Model;

class TpgoodsType extends Model
{

    //关联商品分类表
    public function cate(){
        return $this->belongsTo('TpgoodsCategory','cid','id');
    }

    //关联规格属性分类表
    public function spec(){
        return $this->hasMany('Tpspec','tid','tid');
    }

}