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
namespace app\admin\validate;

use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = [
        ['rolename', 'unique:tprole', '角色已经存在']
    ];

}