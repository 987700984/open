<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Exx extends Base{
	private $url = 'https://api.exx.com/data/v1/';

	public function tickers(){
		$this->url .= 'tickers';

		if(cache('Exx')){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = cache('Exx');
			return json($this->data);
		}
		
		$result = json_decode(mycurl($this->url),true);
		$this->getPrice();

		foreach ($result as $key => $value){

			if($value['vol'] == 0){
				continue;
			}

			$f = explode('_',$key);
			if($f[1]=='btc'){
              	$res['name'] = strtoupper($f[0]);
			    $res['volume'] = $value['vol'];
				$res['sell'] = $value['last']*$this->Pbtc;
				$res['high'] = $value['high']*$this->Pbtc;
				$res['low'] = $value['low']*$this->Pbtc;
 			    $r[] = $res;             
			}
			// elseif($f[1] == 'eth'){
			// 	$res['sell'] = $value['last']*$this->Peth;
			// 	$res['high'] = $value['high']*$this->Peth;
			// 	$res['low'] = $value['low']*$this->Peth;
			// }elseif($f[1] == 'usdt'){
			// 	$res['sell'] = $value['last']*$this->Pusdt;
			// 	$res['high'] = $value['high']*$this->Pusdt;
			// 	$res['low'] = $value['low']*$this->Pusdt;
			// }


		}

		if($r){
			cache('Exx',$r,60);
			$this->data['data'] = $r;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '获取失败';			
			return json($this->data);
		}		
	}

	public function trades(){
		$this->url .= 'trades?';
		$currency = input('currency');
		if(empty($currency)){
			$this->data['msg'] = 'missing parameter';
		}
		$this->url .= 'currency='.$currency;
		$result = json_decode(mycurl($this->url),true);
		if($result){
			$this->data['data'] = $result;
			$this->data['msg'] = 'Success';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			return json($this->data);
		}			
	}

}