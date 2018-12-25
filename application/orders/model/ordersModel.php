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
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 数据库连接DSN配置
        'dsn'         => '',
        // 服务器地址
        'hostname'    => '47.52.205.230',
        // 数据库名
        'database'    => 'test_pay',
        // 数据库用户名
        'username'    => 'test_pay',
        // 数据库密码
        'password'    => 'Ki885daaSiXhinyL',
        // 数据库连接端口
        'hostport'    => '',
        // 数据库连接参数
        'params'      => [],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'pay_',
    ];

	protected $table = 'pay_order';
	

    public function getStateAttr($value)
    {
        $status = [-1=>'已退款',0=>'无效',1=>'待付款',2=>'待发货',3=>'已发货'];
        return $status[$value];
    }  

    public function getUptimeAttr($value)
    {
        return date('Y-m-d H:i',$value);
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
}