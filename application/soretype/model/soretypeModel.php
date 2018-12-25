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
namespace app\soretype\model;

use think\Model;

class soretypeModel extends Model
{
	protected $table = 'ims_tpsoretype';

	/**
	 * 根据搜索条件获取币种列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getsoretypeByWhere($where, $offset, $limit)
	{
		return $this->where($where)->limit($offset, $limit)->select();
	}

    /**
     * 根据搜索条件获取所有的币种
     * @param $where
     */
    public function getAllsoretype($where)
    {
    	return $this->where($where)->count();
    }

        /**
     * 根据币种ID获取币种内容
     * @param $soretypeid
     */
    public function getOnesoretype($soretypeid)
    {
        return $this->where('id', $soretypeid)->find();
    }

        /**
     * 编辑币种
     * @param $param
     */
    public function editsoretype($param)
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
     * 新增币种
     * @param $param
     */
    public function insertsore($param)
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
     * 删除订单
     * @param $ordersid
     */
    public function delsore($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }    

}