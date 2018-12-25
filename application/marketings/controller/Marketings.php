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

namespace app\marketings\controller;

use app\admin\controller\Base;


class Marketings extends Base
{

	//默认主页
    public function index()
    {   	
    	$referralcode=session('id');
    	$qrcodeurl='http://admin.2j1.com/'.$referralcode;
    	$this->assign(['qrcodeurl' => $qrcodeurl,'referralcode' => $referralcode]);
    	return $this->fetch();
    }
}
