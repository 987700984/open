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
namespace app\goods\model;

use think\Model;

class categoryModel extends Model
{
	protected $table = 'ims_tpgoods_category';
	
	/**
	 * 根据搜索条件获取商品列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getCategoryByWhere($where, $offset, $limit)
	{
		return $this->where($where)->limit($offset, $limit)->order('orderdisplay,id')->select();
	}

    /**
     * 新增商品
     * @param $param
     */
    public function insertCategory($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 0, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    
    /**
     * 编辑商品
     * @param $param
     */
    public function editCategory($param)
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
    
    /**
     * 删除商品
     * @param $goodsid
     */
    public function delCategory($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 根据商品ID获取商品内容
     * @param $goodsid
     */
    public function getOneCategory($id)
    {
        return $this->where('id', $id)->find();
    }
    
    /**
     * 根据搜索条件获取所有的商品
     * @param $where
     */
    public function getAllCategory($where)
    {
    	return $this->where($where)->count();
    }
    
    public function getGoods()
    {
        return $this->select();
    }


}