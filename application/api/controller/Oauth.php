<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Oauth extends Base{

    //检测是否绑定
    public function index(){
        $oid = get_input_data('oid');
        $type = get_input_data('type');

        $map[$type] = $oid;

        if(!$type){
            $this->data['status'] = 0;
            $this->data['msg'] = '类型为空';
            return json($this->data); 
        }
      
        $c = db::name('oauth')->where($map)->find();

        if($c){
        	$user = db::name('tpuser')->where(['id'=>$c['uid']])->find();
            //生成token
	        $arr = array(
	                'username' => $user['phone'],
	                'password' => $user['password'],
	                'time'     => time(),
	            );          
            unset($user['password']);
            session('user',$user);
	        $res = json_encode($arr);
        	$array = base64_encode($res);

            $this->data['status'] = 1;
            $this->data['msg'] = '登录成功';
            $this->data['data'] = $user;
            $this->data['token'] = $array;
            return json($this->data); 

        }else{
            $this->data['status'] = 0;
            $this->data['msg'] = '暂未绑定';
            return json($this->data);         	
        }
    }

    //检测是否注册
    public function check(){
    	$data['nickname'] = get_input_data('nickname');
    	$data['pic'] = get_input_data('pic');
    	$data['oid'] = get_input_data('oid');
    	$data['type'] = get_input_data('type');
    	$phone = get_input_data('phone');

    	//保存到session
    	session('bind',$data);
    	$user = db::name('tpuser')->where(['phone'=>$phone])->find();

    	if($user){
            $this->data['status'] = 1;
            $this->data['msg'] = '已注册';
            return json($this->data);      		
    	}else{
            $this->data['status'] = 0;
            $this->data['msg'] = '未注册';
            return json($this->data);  
    	}
    }

    public function bind(){
    	$map['phone'] = get_input_data('phone');
    	$map['password'] = MD5(get_input_data('password'));

    	if(!session('bind')){
            $this->data['status'] = 0;
            $this->data['msg'] = '未找到绑定信息';
            return json($this->data); 
    	}

    	$user = db::name('tpuser')->where($map)->find();

    	if($user){
    		$d = session('bind');
    		$data[$d['type']] = $d['oid'];

    		//检测用户是否绑定过
    		$bind = db::name('oauth')->where(['uid' => $user['id']])->find();
//return json($data);
    		if($bind){
   
    			$r = db::name('oauth')->where(['id' => $bind['id']])->update($data);
    		}else{
    			$data['uid'] = $user['id'];
    			$r = db::name('oauth')->insert($data);
    		}

            unset($user['password']);
            session('user',$user);

            //生成token
	        $arr = array(
	                'username' => $user['phone'],
	                'password' => $user['password'],
	                'time'     => time(),
	            );
	        $res = json_encode($arr);
        	$array = base64_encode($res);

            $this->data['status'] = 1;
            $this->data['msg'] = '绑定成功';
            $this->data['data'] = $user;
            $this->data['token'] = $array;            
            return json($this->data); 

    	}else{
            $this->data['status'] = 0;
            $this->data['msg'] = '帐号或密码不正确';
            return json($this->data);     		
    	}
    }

}