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

    //模板参数替换
    'view_replace_str'       => array(
        '__CSS__'    => '/static/admin/css',
        '__JS__'     => '/static/admin/js',
        '__IMG__' => '/static/admin/images',
        '__UIMG__' => '/uploads/share',
        '__EDITOR__' => '/static/editor',

    ),

    //管理员状态
    'user_status' => [
        '1' => '正常',
        '0' => '禁止登录'
    ],
    //角色状态
    'role_status' => [
        '1' => '启用',
        '2' => '禁用'
    ],
    //角色名称
    'role_type' => [
        '1' => '超级管理员',
        '7' => '会员',
        '8' => '钻石代理',
        '9' => '业务员',
        '10' => '运营',
        '11' => '体验代理',
        '12' => '黄金代理',
        '13' => '白银代理',
        '14' => '合伙人',
        '1001' => '线下渠道A级',
        '1002' => '线下渠道B级',
        '0' => '游戏服务器同步用户',
    ],
    //申请代理审核状态
    'agentstatus' => [
        '0' => '审核中',
        '1' => '<font color="green">通过审核</font>',
        '2' => '<font color="red">审核不通过</font>',
    ],
];
