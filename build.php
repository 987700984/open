<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    // 生成运行时目录
    '__dir__'  => ['runtime/cache', 'runtime/log', 'runtime/temp', 'runtime/template'],
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'admin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => [ 'controller', 'model', 'view'],
        'controller' => ['Index', 'Test', 'UserType'],
        'model'      => ['User', 'UserType'],
        'view'       => ['index/index'],
    ],
    'shop'     => [
        '__file__'   => ['common.php', 'config.php'],
        '__dir__'    => ['controller', 'model', 'view'],
        'controller' => ['GoodsType','Orders'],
        'model'      => ['TpgoodsCategory', 'TpgoodsType','Tpspec','TpspecItem','TpgoodsPrice','Order','OrderInfo'],
        'view'       => ['goods_type/index'],
    ],
    // 其他更多的模块定义
];