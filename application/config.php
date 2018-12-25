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

return [
    'url_route_on' => true,
    'url_route_must' => false,
    'trace' => [
        'type' => 'html', // 支持 socket trace file
    ],
    //各模块公用配置
    'extra_config_list' => ['database', 'route', 'validate'],
    //临时关闭日志写入
    'log' => [
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH
        ],

    'app_debug' => true,
    'default_filter' => [ 'htmlspecialchars'],

	// +----------------------------------------------------------------------
	// | 异常设置
	// +----------------------------------------------------------------------	
	'http_exception_template'    =>  [
	    // 定义404错误的重定向页面地址
		404 =>  APP_PATH.'404.html',
		// 还可以定义其它的HTTP status
		401 =>  APP_PATH.'401.html',
	],		

    //短信
    'code' => [
        'key' => '4d2a67c6517cdf83e5db72fe542b2dfd',
        'tpl_id' => '68750',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    'cache' => [
        // 驱动方式
        'type' => 'file',
        // 缓存保存目录
        'path' => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
        'host' => '192.168.6.55',
        'port' => 11211,
    ],

    //加密串
    'salt' => 'wZPb~yxvA!ir38&Z',

    //推广成为下级奖励房卡数量
    'extensionnum' => '1',

    //备份数据地址
    'back_path' => APP_PATH .'../back/',

    // 默认模块名
    'default_module'         => 'admin',
    // 默认控制器名
    'default_controller'     => 'login',
    // 默认操作名
    'default_action'         => 'index', 
        // aliyun OSS
    'OSS_OPEN'      => true,
    'OSS_BUCKET'    => 'btcim',
    'OSS_BUCKET_VIDEO'=> 'lmvideo',
    'OSS_KEY'       => 'LTAIG0xbSTLgFxWn',
    'OSS_SECRET'    => 'dZNQw2gYnpLATgHMXMPGKLXkqdFWvH',
    'OSS_ENDPOINT'  => 'oss-cn-shenzhen.aliyuncs.com',
];