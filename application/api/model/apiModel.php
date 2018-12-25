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
namespace app\api\model;

use think\Model;

class apiModel extends Model
{
	public function candy($phone, $num){
		$user 			 = $this->name('tpuser')->where('phone='.$phone)->find();
		$res 			 = $this->name('tpintegral')->where('uid='.$user['id'].' and sid=-1')->find();
		$data['addtime'] = time();
		if ($res) {
			return $this->name('tpintegral')->where('uid='.$user['id'].' and sid=-1')->setInc('integral', $num);				
		}else{
			$data['sid'] 	  = '-1';
			$data['integral'] = $num;
			$data['uid'] 	  = $user['id'];
			return $this->name('tpintegral')->insert($data);
		}
	}

	//脚本
	public function onedb($name, $p, $row)
	{
		$btcim = $this->connect('mysql://btcimmysql:DCpKPCJhxD@120.79.77.111:3306/btcimmysql#utf8');
		// dump($btcim);exit;
      
      	
		return $btcim->table($name)->limit(($p-1)*$row,$row)->select();
	}

	//脚本
	public function onedb2($name)
	{
		$btcim = $this->connect('mysql://btcimmysql:DCpKPCJhxD@120.79.77.111:3306/btcimmysql#utf8');
		// dump($btcim);exit;
      
      	
		return $btcim->table($name)->select();
	}
}