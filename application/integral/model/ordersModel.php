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
namespace app\orders\model;

use think\Model;

class ordersModel extends Model
{
	protected $table = 'ims_tporders';
	
	/**
	 * 根据搜索条件获取订单列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getordersByWhere($where, $offset, $limit)
	{
		return $this->field('ims_tporders.*,ims_tpgoods.goodsname')
		->join('ims_tpgoods', 'ims_tporders.goodsid = ims_tpgoods.goodsid')
		->where($where)->limit($offset, $limit)->order('ordersid')->select();
	}

    /**
     * 新增订单
     * @param $param
     */
    public function insertorders($param)
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
     * 编辑订单
     * @param $param
     */
    public function editorders($param)
    {
    	try{
    
    		$result = $this->save($param, ['ordersid' => $param['ordersid']]);
    
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
     * 删除订单
     * @param $ordersid
     */
    public function delorders($ordersid)
    {
    	try{
    
    		$this->where('ordersid', $ordersid)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 根据订单ID获取订单内容
     * @param $ordersid
     */
    public function getOneorders($ordersid)
    {
        return $this->where('ordersid', $ordersid)->find();
    }
    
    public function getOneordersByorder_no($order_no)
    {
        return $this->where('order_no', $order_no)->find();
    }
    
    /**
     * 根据搜索条件获取所有的订单
     * @param $where
     */
    public function getAllorders($where)
    {
    	return $this->where($where)->count();
    }

}