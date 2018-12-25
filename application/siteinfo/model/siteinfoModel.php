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
namespace app\siteinfo\model;

use think\Model;

class siteinfoModel extends Model
{
	protected $table = 'ims_siteinfo';


	public function getsiteinfo($key)
	{
		$wallet = $this->where('key',$key)->find();
		if (isset($wallet)) {
			return unserialize($wallet['value']);
		}

	}

    public function siteinfoSave($value, $key)
    {
		try{
    
    		$result = $this->save($value, ['key' => $key]);
    
    		if(false === $result){
    			// 验证失败 输出错误信息
    			return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
    		}else{

    			return ['code' => 1, 'data' => '', 'msg' => '更新成功'];
    		}
    	}catch( PDOException $e){
    		return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
    	}

    }

}