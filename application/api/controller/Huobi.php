<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Huobi extends Base{
	private $url = 'https://api.huobi.pro';

	public function symbols(){
		$this->url .= '/v1/common/currencys';
		$result = json_decode(mycurl($this->url));
		if($result->status=='ok'){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $result->data;
			return json($this->data);			
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}


	} 

	public function tickers(){
		$this->url .= '/market/detail/merged?symbol=';
		$symbols = input('symbols');
		if(empty($symbols)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}
		$result = json_decode(mycurl($this->url.$symbols));
		if($result->status=='ok'){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $result->tick;
			return json($this->data);
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}
	}

	public function kline(){
		$this->url .= '/market/history/kline?';
		$symbols = input('symbols');
 
		$period = input('period','1day');
		$size  = input('size',150);
		if(empty($symbols)){
			$this->data['msg'] = '缺少参数';
			return json($this->data);
		}
		$this->url .= 'symbol='.$symbols.'&period='.$period.'&size='.$size;
//          echo $this->url;die;
		$result = json_decode(mycurl($this->url));
 // dump($result);die;
		if($result->status=='ok'){
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
		$p = input('p',1);
		$row = input('row',20);

		if(cache('Huobi_'.$p)){
			$this->data['data'] = cache('Huobi_'.$p);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}

		$list = json_decode(mycurl('http://api.huobi.pro/v1/common/currencys'),true);
		$list = $list['data'];
		$data = array();
		$this->getPrice();

		for($i=($p-1)*$row;$i<$p*$row;$i++){
			
			$res = json_decode(mycurl('http://api.huobi.pro/market/detail/merged?symbol='.$list[$i].'btc'),true);

			if($res['status']=='ok'){
				$r['name'] = strtoupper($list[$i]);//名称
				$r['sell'] = $res['tick']['close']*$this->Pbtc;//最新成交价
				$r['high'] = $res['tick']['high']*$this->Pbtc;//24H最高价
				$r['low'] = $res['tick']['low']*$this->Pbtc;//24H最低价
				$r['volume'] = $res['tick']['amount'];//24H成交量
				$data[] = $r;
			}
			
		}

		if($data){
			cache('Huobi_'.$p,$data,60);
			$this->data['data'] = $data;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '获取失败';
			return json($this->data);
		}
	}
  
 	public function getList2(){
		$p = input('p',1);
		$row = input('row',20);
/*
		if(cache('Huobi_'.$p)){
			$this->data['data'] = cache('Huobi_'.$p);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}*/

		$list = json_decode(mycurl('http://api.huobi.pro/v1/common/currencys'),true);
		$list = $list['data'];
		$data = array();
		$this->getPrice();

		for($i=($p-1)*$row;$i<$p*$row;$i++){
			
			$res = json_decode(mycurl('http://api.huobi.pro/market/detail/merged?symbol='.$list[$i].'btc'),true);

			if($res['status']=='ok'){
				$r['name'] = strtoupper($list[$i]);//名称
				$r['sell'] = $res['tick']['close'];//最新成交价
				$r['high'] = $res['tick']['high'];//24H最高价
				$r['low'] = $res['tick']['low'];//24H最低价
				$r['volume'] = $res['tick']['amount'];//24H成交量
				$r['change'] = round(($res['tick']['open']-$res['tick']['close'])/$res['tick']['open'],2);//涨跌幅
                $r['symbol'] = $list[$i].'btc';
				$data[] = $r;
			}
			
		}

		if($data){
			cache('Huobi_'.$p,$data,60);
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