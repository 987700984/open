<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Coinbase extends Base{
	private $url = 'https://www.coinbase.com/api/v2/prices/';

	public function btcusd(){
		$period = get_input_data('period','hour');
		$this->url .= 'BTC-USD/historic?period='.$period;
		$result = json_decode(mycurl($this->url));
		$this->data['status'] = 1;
		$this->data['msg'] = 'Success';
		$this->data['data'] = $result->data->prices;
		return json($this->data);		

	}

	public function ethusd(){
		$period = get_input_data('period','hour');
		$this->url .= 'ETH-USD/historic?period='.$period;
		$result = json_decode(mycurl($this->url));
		$this->data['status'] = 1;
		$this->data['msg'] = 'Success';
		$this->data['data'] = $result->data->prices;
		return json($this->data);		

	}	

	public function ltcusd(){
		$period = get_input_data('period','hour');
		$this->url .= 'LTC-USD/historic?period='.$period;
		$result = json_decode(mycurl($this->url));
		$this->data['status'] = 1;
		$this->data['msg'] = 'Success';
		$this->data['data'] = $result->data->prices;
		return json($this->data);		

	}	
}