<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Purse extends Common{
	//获取积分
	public function getMoney(){
		$money = db::name('tpintegral') ->where('uid='.session('user.id').' and sid=-1')->value('integral');
		if($money){
			session('user.jifen',$money);
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = array('jifen'=>$money);
			return json($this->data);
		}
		$this->data['msg'] = '获取失败';
		return json($this->data);
	}

	//获取积分账单
	public function getBill(){
		$p = get_input_data('p',1);
		$row = get_input_data('row',20);
		// $money2 = M('money2');
		//总页数
		$num = db::name('tpbill')->where('uid='.session('user.id'))->count();
        $data['allpage'] = ceil($num/$row);
		$data['list'] = db::name('tpbill')->where('id='.session('user.id'))->order('addtime desc')->limit(($p-1)*$row.','.$row)->select();
		$this->data['status'] = 1;
		$this->data['msg'] = '获取成功';
		$this->data['data'] = $data;
		return json($this->data);

	}
}