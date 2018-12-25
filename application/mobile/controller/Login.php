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

namespace app\mobile\controller;

use think\Controller;
use org\Verify;
use think\Db;

class Login extends Controller
{

	//默认主页
    public function index()
    {   	
    	$referralcode=input('id');
    	$this->assign(['referralcode' => $referralcode,]);
        $this->redirect(url('Usercenter/index',['parentid'=>$referralcode]));
    	//return $this->fetch(url('Usercenter/index',['parentid'=>$referralcode]));
    }


    //登录页
    public function login(){
    	if(!empty(session('username')) || !empty(session('openid'))){
    		$username = session('username');
    		$openid = session('openid');
	    }else{
	        $username = '';
	        $openid = '';
	    }
    	if(request()->isPost()){
    		$param = input('param.');
            $param = parseParams($param['data']);
            $username = $param['username'];
            $password = $param['password'];
            /*
            $verify = new Verify();
            if (!$verify->check($param['yzm'])) {
                    return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
            }
            */
            $tpuserinfo = Db::name('tpuser')->where('username',$username)->find();
            if(empty($tpuserinfo)){
            	return json(['code' => -4, 'data' => '', 'msg' => '用户不存在']);
            }

            if(md5($password) != $tpuserinfo['password']){
            return json(['code' => -4, 'data' => '', 'msg' => '密码错误']);
	        }

	        if(1 != $tpuserinfo['status']){
	            return json(['code' => -6, 'data' => '', 'msg' => '该账号被禁用']);
	        }
	        //更新管理员状态
	        $param = [
	            'loginnum' => $tpuserinfo['loginnum'] + 1,
	            'last_login_ip' => request()->ip(),
	            'last_login_time' => time(),
	            'openid' => $openid,
	        ];

	        Db::name('tpuser')->where('id', $tpuserinfo['id'])->update($param);
	        session('id', $tpuserinfo['id']);
	        return json(['code' => 1, 'data' => url('Usercenter/index'), 'msg' => '登录成功']);    
    	}
	    $this->assign('username',$username); 
    	return $this->fetch();
    }

        //验证码
    public function checkVerify()
    {
        $verify = new Verify();
        $verify->imageH = 48;
        $verify->imageW = 100;
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }


}
