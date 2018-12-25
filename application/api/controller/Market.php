<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Market extends Base{
	public function getCoin(){
		$p = get_input_data('p',1);
		$row = get_input_data('row',20);

		if(cache('self_coin_'.$p)){
			$this->data['data'] = cache('self_coin_'.$p);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}

		$res = db::connect('mysql://root:f73172dbc6679878@47.75.75.134:3306/market#utf8')->name('mk_coins')->where('status=1')->order('des,id')->select();

		if($res){
			cache('self_coin_'.$p,$res,3600);
			$this->data['data'] = $res;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);			
		}else{

			$this->data['msg'] = '获取失败';
			$this->data['status'] = 0;
			return json($this->data);				
		}
	}

	public function getMarket(){
		$p = get_input_data('p',1);
		$row = get_input_data('row',20);

		if(cache('self_market_'.$p)){

			$this->data['data'] = cache('self_market_'.$p);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}

		$res = db::connect('mysql://root:f73172dbc6679878@47.75.75.134:3306/market#utf8')->name('mk_market')->order('addtime desc,id')->limit($p*$row,$row)->select();

		if($res){

			cache('self_market_'.$p,$res,60);

			$this->data['data'] = $res;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
	
			return json($this->data);	

		}else{

			$this->data['msg'] = '获取失败';
			$this->data['status'] = 0;
			return json($this->data);				
		}		
	}

	public function getKline(){
		$row = get_input_data('row',150);
		$map['coin'] = get_input_data('coin');
		$map['type'] = get_input_data('type');
 
       if(!$map['coin']){    
			$this->data['msg'] = '缺少参数';
			$this->data['status'] = 0;
			return json($this->data);         
       }

       if(!$map['type']){
			$this->data['msg'] = '缺少参数';
			$this->data['status'] = 0;
			return json($this->data);         
       }

		if(cache('self_kline_'.$map['coin'].'_'.$map['type'])){
			$this->data['data'] = cache('self_kline_'.$map['coin'].'_'.$map['type']);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}
        
        $model = db::connect('mysql://root:f73172dbc6679878@47.75.75.134:3306/market#utf8');
		$res = $model->name('mk_kline')->where($map)->order('addtime desc,id')->limit($row)->select();	

		if($res){

			cache('self_kline_'.$map['coin'].'_'.$map['type'],$res,60);

			$this->data['data'] = $res;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
	
			return json($this->data);	

		}else{

			$this->data['msg'] = '获取失败';
			$this->data['status'] = 0;
			return json($this->data);				
		}		
	}

}