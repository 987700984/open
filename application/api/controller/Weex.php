<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Weex extends Base{
	private $url = 'https://api.weex.com';

	public function symbols(){
		$data = array('btcusd','bccusd','ltcusd','ethusd','zecusd','dasjusd');
		if($data){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $data;
			return json($this->data);			
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}


	} 

	public function ticker(){
		$this->url .= '/v1/market/ticker?market=';
		$symbols = get_input_data('symbols');
		if(empty($symbols)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}		
		$result = json_decode(mycurl($this->url.$symbols));
		if($result->message=='Ok'){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $result->data;
			return json($this->data);			
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}		
	}

	public function kline(){
		$this->url .= '/v1/market/kline?market=';
		$symbols = get_input_data('symbols');
		$type =get_input_data('type','1day');
		if(empty($symbols)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}	
		$this->url .= $symbols.'&type='.$type;	
		$result = json_decode(mycurl($this->url));
		if($result->message=='Ok'){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $result->data;
			return json($this->data);			
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}			
	}

	public function getList(){
		$list = array('btcusd','bccusd','ltcusd','ethusd','zecusd','dasjusd');
		$data = array();
		if(cache('Weex')){
			$this->data['data'] = cache('Weex');
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}
		foreach ($list as $key => $value) {
			$res = json_decode(mycurl('https://api.weex.com/v1/market/ticker?market='.$value),true);

			if($res['message']=='Ok'){

				$r['name'] = strtoupper(str_replace('usd','',$value));//名称
				$r['sell'] = $res['data']['ticker']['last'];//最新成交价
				$r['high'] = $res['data']['ticker']['high'];//24H最高价
				$r['low'] = $res['data']['ticker']['low'];//24H最低价
				$r['volume'] = $res['data']['ticker']['vol'];//24H成交量

				$data[] = $r;
			}
			
		}

		if($data){
			cache('Weex',$data,60);
			$this->data['data'] = $data;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}
	}	

}