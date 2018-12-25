<?php
namespace app\orders\model;
use think\Model;

class goodsModel extends Model
{
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 数据库连接DSN配置
        'dsn'         => '',
        // 服务器地址
        'hostname'    => '47.52.205.230',
        // 数据库名
        'database'    => 'pay',
        // 数据库用户名
        'username'    => 'pay',
        // 数据库密码
        'password'    => 'cdc5t36pWw5BNwJG',
        // 数据库连接端口
        'hostport'    => '',
        // 数据库连接参数
        'params'      => [],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'pay_',
    ];

	protected $table = 'pay_order_info';
	      
}