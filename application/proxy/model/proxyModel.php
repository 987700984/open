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
namespace app\proxy\model;

use think\Model;

class proxyModel extends Model
{
	protected $table = 'ims_proxy';

		/**
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getproxyByWhere($where, $offset, $limit)
	{

		return $this->where($where)->limit($offset, $limit)->select();

	}

	public function getAllproxy($where)
	{
    	return $this->where($where)->count();
	}

	public function agent(){
		$id = $_SESSION['think']['id'];
		$res = $this->name('ims_proxy')->where('id='.$id)->find();	
	}

	public function proxyAdd($param)
	{
        try{

            $result =  $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
	}

	public function proxyEdit($param)
	{
		try{
    
    		$result = $this->save($param, ['id' => $param['id']]);
    
    		if(false === $result){
    			// 验证失败 输出错误信息
    			return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
    		}else{

    			return ['code' => 0, 'data' => '', 'msg' => '编辑成功'];
    		}
    	}catch( PDOException $e){
    		return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }

	public function oneproxy($id)
	{
		return $this->where('id='.$id)->find();
	}

    public function delproxy($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    } 
  
  	public function setKey($id)
  	{
  		try{
  			$param = set_key();
    		
    		$result = $this->save($param, ['id' => $id]);
    		return ['code' => 0, 'data' => '', 'msg' => '秘钥设置成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
  	}
}