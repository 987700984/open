<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Price extends Base{
	// 获取列表
	// p           开始
	// row         条数
	// convert     参考币
	public function getList(){
		$start = get_input_data('p');
		$limit = get_input_data('row');
		$convert = get_input_data('convert');

		if(cache('coinmarketcap_'.$start)){
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = cache('coinmarketcap_'.$start);
			return json($this->data);
		}

		$url = 'https://api.coinmarketcap.com/v1/ticker/?';
		if($start!=null) $url .= 'start='.$start.'&';
		if($limit!=null) $url .= 'limit='.$limit.'&';
		if($convert!=null) $url .= 'convert='.$convert.'&';
		$result = json_decode(mycurl($url),true);
	
		$data = array();
		foreach ($result as $key => $value) {
			$r['name'] = $value['symbol'];//名称
			$r['sell'] = $value['price_usd'];//最新成交价
			$r['high'] = 0;//24H最高价
			$r['low'] = 0;//24H最低价
			$r['volume'] = $value['24h_volume_usd'];//24H成交量
			$data[] = $r;
		}
		
		if($data){
			cache('coinmarketcap_'.$start,$data,60);
			$this->data['status'] = 1;
			$this->data['msg'] = '获取成功';
			$this->data['data'] = $data;
			return json($this->data);
		}
		$this->data['msg'] = '获取失败';
		return json($this->data);

	}

	// 获取详情
	// id          币ID
	// convert     参考币
	public function details(){
		$url = 'https://api.coinmarketcap.com/v1/ticker/';
		$id = get_input_data('id');
		$convert = get_input_data('convert');
		if($id==null){
			$this->data['msg'] = 'missing parameter';
		}else{
			$url .= $id.'/?';
			if($convert!=null) $url .= 'convert='.$convert.'&';
			$result = json_decode(mycurl($url));
			if($result){
				if($result->error){
					$this->data['msg'] = $result->error;
				}else{
					$this->data['status'] = 1;
					$this->data['msg'] = 'Success';
					$this->data['data'] = $result;					
				}

			}			
		}
		return json($this->data);
	}

	// 获取全球数据
	// convert     参考币
	public function getGlobal(){
		$url = 'https://api.coinmarketcap.com/v1/global/?';
		$convert = get_input_data('convert');
		if($convert!=null) $url .= 'convert='.$convert;
		$result = json_decode(mycurl($url));
		if($result){
			$this->data['status'] = 1;
			$this->data['msg'] = 'Success';
			$this->data['data'] = $result;
		}
		return json($this->data);		

	}


}