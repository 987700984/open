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
namespace app\integral\model;

use think\Model;

class integralModel extends Model
{
	protected $table = 'ims_tpintegral';

	/**
	 * 根据搜索条件获取币种列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getintegralByWhere($where, $offset, $limit)
	{  
        $sql = 'select i.id,i.usable,s.name,i.uid,i.money,m.username,m.phone,i.addtime,i.integral from ims_tpintegral as i inner join ims_tpuser as m on m.id=i.uid inner join ims_tpsoretype as s on s.id=i.sid where 1=1  ' .$where.'  order by i.id,i.integral desc limit ' .$offset. ',' .$limit;
//        echo $sql;die;
        return $this->query($sql);
		// return $this->where($where)->limit($offset, $limit)->select();
	}

    /**
     * 根据搜索条件获取所有的币种
     * @param $where
     */
    public function getAllintegral($where)
    {
        $sql = 'select i.id,s.name,i.uid,m.username,m.phone,i.addtime,i.integral from ims_tpintegral as i inner join ims_tpuser as m on m.id=i.uid inner join ims_tpsoretype as s on s.id=i.sid where 1=1 '.$where;
        return $this->query($sql);
    	// return $this->where($where)->count();
    }

        /**
     * 根据币种ID获取币种内容
     * @param $integralid
     */
    public function getOneintegral($integralid)
    {
        return $this->where('id', $integralid)->find();
    }

        /**
     * 编辑币种
     * @param $param
     */
    public function editintegral($param)
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


}