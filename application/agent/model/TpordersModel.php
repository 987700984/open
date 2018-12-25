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
namespace app\agent\model;

use think\Model;

class TpordersModel extends Model
{
    protected $table = 'ims_tporders';

    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getOrdersByWhere($where, $offset, $limit)
    {
        return $this->field('ims_tporders.*,username')
            ->join('ims_tpuser', 'ims_tporders.orderscreatepersonid = ims_tpuser.id')
            ->where($where)->limit($offset, $limit)->order('id')->select();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllOrders($where)
    {
        return $this->where($where)->count();
    }

}