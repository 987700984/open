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

class Log extends Model
{

    /*新增日志*/

    public function  addLog($data,$content='未知操作'){
          $data['content'] =$content;
         return $this->save($data);
    }

    public function  getAddtimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }


}