<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Bitfinex extends Base{
	private $url = 'https://api.bitfinex.com/v1/';

	public function symbols(){
		$this->url .= 'symbols';
		$result = json_decode(mycurl($this->url));
		if($result){
			$this->data['data'] = $result;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			return json($this->data);
		}
	}

	public function tickers(){
        $this->url .= 'pubticker/';
		$coin = get_input_data('symbols');
		if(empty($coin)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}
		$this->url .= $coin;
		$result = json_decode(mycurl($this->url));
		if($result){
			$this->data['data'] = $result;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			return json($this->data);
		}		
	}

	public function getList(){

		$p = get_input_data('p',1);
		$row = get_input_data('row',20);

		if(cache('bitfinex_'.$p)){
			$this->data['data'] = cache('bitfinex_'.$p);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);			
		}
		$list = json_decode(mycurl('https://api.bitfinex.com/v1/symbols'),true);
		$data = array();

		for($i=($p-1)*$row;$i<$p*$row;$i++){
			if(substr($list[$i],-3) == 'usd'){
	
				$res = json_decode(mycurl('https://api.bitfinex.com/v1/pubticker/'.$list[$i]),true);
				$r['name'] = strtoupper(str_replace('usd','',$list[$i]));;//名称
				$r['sell'] = $res['last_price'];//最新成交价
				$r['high'] = $res['high'];//24H最高价
				$r['low'] = $res['low'];//24H最低价
				$r['volume'] = $res['volume'];//24H成交量
				$data[] = $r;

			}
		}		

		if($data){
			cache('bitfinex_'.$p,$data,60);
			$this->data['data'] = $data;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}
	}

	public function book(){
		$this->url .= 'book/';
		$coin = strtoupper(get_input_data('symbols'));
		if(empty($coin)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}
		$this->url .= $coin;		
		$result = json_decode(mycurl($this->url));
		if($result){
			$this->data['data'] = $result;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			return json($this->data);
		}	
	}

}