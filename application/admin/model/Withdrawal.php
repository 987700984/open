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
namespace app\admin\model;

use think\Model;

class Withdrawal extends Model
{




    /**
     * 与user表关联
     * @param $param
     */
    public function user()
    {
        return $this->belongsTo('UserModel','uid','id');
    }










}