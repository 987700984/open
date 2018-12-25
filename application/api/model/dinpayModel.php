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
namespace app\api\model;

use think\Model;

class dinpayModel extends Model
{
    protected $table = 'ims_tpdinpaynotify';
    
	public  function index(){
		return '';
	}
	
	public function getOnenotify($order_no)
	{
	    return $this->where('order_no', $order_no)->find();
	}
	
	public function insertnotify($param)
	{
	    try{
	
	        $result =  $this->save($param);
	        if(false === $result){
	            // 验证失败 输出错误信息
	            return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
	        }else{
	
	            return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
	        }
	    }catch( PDOException $e){
	
	        return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
	    }
	}

}
