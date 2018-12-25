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

// [ 应用入口文件 ]
header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求

// 定义应用目录
define('CALBACK','http://open.demo.168erp.cn');
//define('CALBACK','https://open.168erp.cn');
define('PAY_URL','http://pay.demo.168erp.cn');
//define('PAY_URL','http://pay.168erp.cn');

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 定义应用缓存目录
define('RUNTIME_PATH', __DIR__ . '/../runtime/');
// 开启调试模式
define('APP_DEBUG', true);
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
