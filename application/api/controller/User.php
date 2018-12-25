<?php

namespace app\api\controller;

use app\orders\model\goodsModel;
use app\orders\model\ordersModel;
use think\Request;
use app\admin\model\UserType;
use think\Db;



class User extends Common{

	//获取所有积分种类
	public function getCoin(){

		$arr = db::name('tpsoretype')->where('status=1')->select();
		$data = [];
		foreach ($arr as $key => $value) {

			$res = db::name('tpintegral')->where('sid='.$value['id'].' and uid='.session('user.id'))->find();
			if ($res) {
				$res['name'] = $value['name'];
				$res['integral'] =sprintf("%.2f",$res['integral']);
				$data[] = $res;

			}else{
				$res['id'] = 0;
				$res['addtime'] = "0";
				$res['frozen'] = null;
				$res['uid'] = session('user.id');
				$res['integral'] = 0;
				$res['sid'] = $value['id'];
				$res['usable'] = 0;
				$res['commission'] = 0;
				$res['name'] = $value['name'];
				$data[] = $res;
			}
		}

		$this->data['status'] = 1;

		$this->data['msg'] = '获取成功';

		$this->data['data'] = $data;

		return json($this->data);

	}

    // 发送验证码。
    public function send(){
        $phone = session('user.phone');
        $code = rand(100000,999999);
        if(empty($phone)){
            $this->data['msg'] = '手机号为空';
            return json($this->data);
        }
        $flag = $this->afs_api();
        if(!$flag){
            $this->data['msg'] = '验证失败';
            return json($this->data);
        }
        //IP检测
//        $ip = getIp();
//        if(cache($ip)){
//            $num = cache($ip)+1;
//            cache($ip,$num);
//            if($num>5){
//                $this->data['msg'] = '操作太频繁';
//                return json($this->data);
//            }
//        }else{
//            cache($ip,1,300);
//        }
        $result = code($phone,$code);

        if($result == 2){
            $this->data['msg'] = '操作太频繁';
            return json($this->data);
        }elseif ($result == 1){

            $this->data['status'] = 1;
            $this->data['msg'] = '发送成功';
            return json($this->data);
        }else{
            $this->data['msg'] = '发送失败';
            return json($this->data);
        }
    }
		//获取积分流水

	public function getCoinBill(){

		$p = get_input_data('p',1);

		$row = get_input_data('row',20);

		$sid = get_input_data('sid');

		if(empty($sid)){

			$this->data['msg'] = '币种ID为空';

			return json($this->data);

		}else{			

			$data['list'] = db::name('tpbill')->where('uid='.session('user.id').' AND sid='.$sid)->order('addtime desc')->limit(($p-1)*$row.','.$row)->select();

			$data['allpage'] = db::name('tpbill')->where('uid='.session('user.id').' AND sid='.$sid)->count();

			$this->data['status'] = 1;

			$this->data['msg'] = '获取成功';

			$this->data['data'] = $data;

			return json($this->data);			

		}

	}

	//糖果转账流水
    public function getUserBill(){

        $p = get_input_data('p',1);

        $row = get_input_data('row',20);

        $sid = get_input_data('sid');

        if(empty($sid)){

            $this->data['msg'] = '币种ID为空';

            return json($this->data);

        }else{

            $res = db::name('tpbill')
                ->field('id,uid,content,ordersn,addtime,price,type,type2,payee')
                ->where(['type2'=>2,'uid'=>session('user.id'),'sid'=>$sid])
                ->order('addtime desc')
                ->limit(($p-1)*$row,$row)
                ->select();

            if (!$res) {
                $this->data['msg'] = '暂无数据';
                return json($this->data);
            }

            foreach ($res as $key => $value) {
                if($value['payee'] != 0){
                    $pay = Db::name('tpuser')
                        ->field('username,phone')
                        ->where(['id'=>$value['payee']])
                        ->find();
                    $res[$key]['username'] = $pay['username'];
                    $res[$key]['phone'] = $pay['phone'];
                }else{
                    $res[$key]['username'] = 'wetoken管理员';
                    $res[$key]['phone'] = '0000000000000';
                }
            }
            $total =  db::name('tpbill')->where(['type2'=>2,'uid'=>session('user.id'),'sid'=>$sid])->field('id')->select();
            $result['list'] = $res;
            $result['total'] = count($total);

            $this->data['status'] = 1;

            $this->data['msg'] = '获取成功';

            $this->data['data'] = $result;

            return json($this->data);

        }

    }

	//糖果交易
	public function giveScore(){
	    $uid = session('user.id');
        //检测是否设置支付方式
        $user = Db::name('tpuser')->where(['id'=>$uid])->find();

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                $this->data['data'] = 2;
                return json($this->data);
            }
        }else{
            $this->data['msg'] = "请先实名认证";
            $this->data['data'] = 3;
            return json($this->data);
        }

        //检测是否关小黑屋
        if(!$user['ispay']){
            $this->data['msg'] = "emmmmm,请联系管理员";
            $this->data['data'] = 4;
            return json($this->data);
        }

		$id = session('user.id');
		$uid = get_input_data('uid');
		$num = abs(intval(get_input_data('num')));
		$sid = get_input_data('sid', '7');
		$content = get_input_data('content');
		$password = get_input_data('password');
		if (empty($password)) {
			$this->data['msg'] = '请输入密码';
			return json($this->data);
		}
		if(empty($uid)){
			$this->data['msg'] = '未填写转账用户id';
			return json($this->data);
		}
		if($id==$uid){
			$this->data['msg'] = '不能填写自己的ID';
			return json($this->data);
		}      
		if(empty($num)){
			$this->data['msg'] = '未填写转账数量';
			return json($this->data);
		}
		if(empty($content)){
			$this->data['msg'] = '未填写备注';
			return json($this->data);
		}
		$result = db::name('tpuser')->where('id='.$id)->find();
		if ($result['password'] != md5($password)) {
			$this->data['msg'] = '密码错误';
			return json($this->data);
		}
        //检测是否存在这个用户
		$user = db::name('tpuser')->where(['id'=>$uid])->find();
		if(!$user){
			$this->data['msg'] = '该用户不存在';
			return json($this->data);			
		}
		$sn = microtime_float();
      	$soretype = db::name('tpsoretype')->where("id=$sid")->find();

      	$min_proc = $soretype['min_give'];
      	$max_proc = $soretype['max_give'];
      	$min_num  = $soretype['num_give'];
		$proc     = $soretype['give'];
		if ($num < $min_num) {
			$this->data['msg'] = '最低转账数量为'.$min_num;
			return json($this->data);
		}
		$proc_num = $num * $proc;
		if ($proc_num < $min_proc) {
			$proc_num = $min_proc;
		}
		if ($proc_num > $max_proc) {
			$proc_num = $max_proc;
		}

		$integral = db::name('tpintegral')->field('integral,usable')->where('uid='.$id.' and sid='.$sid)->find();

		if ($integral){
		    //可用额度
//		    if($num > $integral['usable']){
//                $this->data['msg'] = '当前可用余额不足';
//                return json($this->data);
//            }

            //总金额
            if($num+$proc_num > $integral['integral']){
                $this->data['msg'] = '当前余额不足';
                return json($this->data);
            }

			$data = array(
					'sid'     => $sid,
					'ordersn' => $sn,
					'uid'     => $id,
					'addtime' => time(),
					'content' => $content,
					'payee'   => $uid,
					'price'   => $num,
					'proc'    => $proc_num,
					'type'	  => 1,
					'type2'	  => 2,
				);
			$data2 = array(
					'sid'     => $sid,
					'ordersn' => $sn,
					'uid'     => $uid,
					'addtime' => time(),
					'payee'  => $id,
					'content' => $content,
					'price'   => $num,
					'type'	  => 0,
					'type2'	  => 2,
				);
			$res = db::name('tpintegral')->where('uid='.$id.' and sid='.$sid)->setDec('integral', $num+$proc_num);
			//$res = db::name('tpintegral')->where('uid='.$id.' and sid='.$sid)->setDec('usable', $num);

			//查看用户是否为老用户
			$check = db::name('tpintegral')->where('uid='.$uid.' and sid='.$sid)->find();
			if($check){
				$res += db::name('tpintegral')->where('uid='.$uid.' and sid='.$sid)->setInc('integral', $num);
			}else{
				$d['uid'] = $uid;
				$d['sid'] = $sid;
				$d['integral'] = $num;
				$d['addtime'] = time();
				db::name('tpintegral')->insert($d);
				$res++;	
			}
          
			db::name('tpbill')->insert($data);
			db::name('tpbill')->insert($data2);
		}else{
			$this->data['msg'] = '当前余额不足';
			return json($this->data);
		}
		
		//最后操作
		if ($res == 2) {
			// db::name('tpexchange')->insert($arr);
			$this->data['status'] = 1;
			$this->data['msg'] = '转账成功';
			return json($this->data);
		}else{
			$this->data['msg'] = '转账失败';
			return json($this->data);
		}


	}


    //糖果转账
    public function give(){
        $uid = session('user.id');
        //检测是否设置支付方式
        $user = Db::name('tpuser')->where(['id'=>$uid])->find();

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                $this->data['data'] = 2;
                return json($this->data);
            }
        }else{
            $this->data['msg'] = "请先实名认证";
            $this->data['data'] = 3;
            return json($this->data);
        }

        //检测是否关小黑屋
        if(!$user['ispay']){
            $this->data['msg'] = "emmmmm,请联系管理员";
            $this->data['data'] = 4;
            return json($this->data);
        }

        $id = session('user.id');
        $uid = get_input_data('uid');
        $num = abs(intval(get_input_data('num')));
        $sid = get_input_data('sid', '7');
        $content = get_input_data('content');
        $password = get_input_data('password');
        if (empty($password)) {
            $this->data['msg'] = '请输入密码';
            return json($this->data);
        }
        if(empty($uid)){
            $this->data['msg'] = '未填写转账用户id';
            return json($this->data);
        }
        if($id==$uid){
            $this->data['msg'] = '不能填写自己的ID';
            return json($this->data);
        }
        if(empty($num)){
            $this->data['msg'] = '未填写转账数量';
            return json($this->data);
        }
        if(empty($content)){
            $this->data['msg'] = '未填写备注';
            return json($this->data);
        }
        $result = db::name('tpuser')->where('id='.$id)->find();
        if ($result['password'] != md5($password)) {
            $this->data['msg'] = '密码错误';
            return json($this->data);
        }
        //检测是否存在这个用户
        $user = db::name('tpuser')->where(['id'=>$uid])->find();
        if(!$user){
            $this->data['msg'] = '该用户不存在';
            return json($this->data);
        }
        $sn = microtime_float();
        $soretype = db::name('tpsoretype')->where("id=$sid")->find();

        $min_proc = $soretype['min_give'];
        $max_proc = $soretype['max_give'];
        $min_num  = $soretype['num_give'];
        $proc     = $soretype['give'];
        if ($num < $min_num) {
            $this->data['msg'] = '最低转账数量为'.$min_num;
            return json($this->data);
        }
        $proc_num = $num * $proc;
        if ($proc_num < $min_proc) {
            $proc_num = $min_proc;
        }
        if ($proc_num > $max_proc) {
            $proc_num = $max_proc;
        }

        $integral = db::name('tpintegral')->field('integral,usable')->where('uid='.$id.' and sid='.$sid)->find();

        if ($integral){
            //可用额度
//            if($num > $integral['usable']){
//                $this->data['msg'] = '当前可用余额不足';
//                return json($this->data);
//            }

            //总金额
            if($num+$proc_num > $integral['integral']){
                $this->data['msg'] = '当前余额不足';
                return json($this->data);
            }

            $data = array(
                'sid'     => $sid,
                'ordersn' => $sn,
                'uid'     => $id,
                'addtime' => time(),
                'content' => $content,
                'payee'   => $uid,
                'price'   => $num,
                'proc'    => $proc_num,
                'type'	  => 1,
                'type2'	  => 2,
            );
            $data2 = array(
                'sid'     => $sid,
                'ordersn' => $sn,
                'uid'     => $uid,
                'addtime' => time(),
                'payee'  => $id,
                'content' => $content,
                'price'   => $num,
                'type'	  => 0,
                'type2'	  => 2,
            );
            $res = db::name('tpintegral')->where('uid='.$id.' and sid='.$sid)->setDec('integral', $num+$proc_num);
           // $res = db::name('tpintegral')->where('uid='.$id.' and sid='.$sid)->setDec('usable', $num);

            //查看用户是否为老用户
            $check = db::name('tpintegral')->where('uid='.$uid.' and sid='.$sid)->find();
            if($check){
                $res += db::name('tpintegral')->where('uid='.$uid.' and sid='.$sid)->setInc('integral', $num);
            }else{
                $d['uid'] = $uid;
                $d['sid'] = $sid;
                $d['integral'] = $num;
                $d['addtime'] = time();
                db::name('tpintegral')->insert($d);
                $res++;
            }

            db::name('tpbill')->insert($data);
            db::name('tpbill')->insert($data2);
        }else{
            $this->data['msg'] = '当前余额不足';
            return json($this->data);
        }

        //最后操作
        if ($res == 2) {
            // db::name('tpexchange')->insert($arr);
            $this->data['status'] = 1;
            $this->data['msg'] = '转账成功';
            return json($this->data);
        }else{
            $this->data['msg'] = '转账失败';
            return json($this->data);
        }

    }


	//购买币//////////////orderid没开发
	public function buy(){

		$id = session('user.id');
		$sid = get_input_data('sid', '7');
		$address = get_input_data('address');
		$num = get_input_data('num');

        //积分比例
        $soreType = db::name('tpsoretype')->field('id,name,status,exchange,time,exchange_num')->where(array('id' => $sid))->find();

        if($soreType['time'] > time()){
            $this->data['msg'] = '暂未开放';
            return json($this->data);
        }

		if(empty($address)){
			$this->data['msg'] = '未填写钱包地址';
			return json($this->data);
		}
		if(empty($num)){
			$this->data['msg'] = '未填写数量';
			return json($this->data);
		}

        if($num <$soreType['exchange_num']){
            $this->data['msg'] = '低于最低兑换数量';
            return json($this->data);
        }

        $integral = db::name('tpintegral')->field('sid,integral,usable,frozen')->where('uid='.$id.' AND sid='.$sid)->find();
//          dump($integralType);die;
        //限额
        if ($integral['usable'] < $num * $soreType['exchange']) {
            $this->data['msg'] = '可用额度不足';
            return json($this->data);
        }
        if ($integral['integral'] < $num * $soreType['exchange']) {
            $this->data['msg'] = '余额不足';
            return json($this->data);
        }
//			$soreType = db::name('soretype')->field('id,name,status,exchange')->where(array('id' => $sid))->find();
        if ($soreType) {
            $arr = array(
                'uid'     => $id,
                'address' => $address,
                'price'   => $num,
                'sid'     => $sid,
                'addtime' => time(),
            );
            $a = db::name('tpintegral')
                ->where('uid='.$id.' AND sid='.$sid)
                ->update(array('integral' => $integral['integral']- ($num * $soreType['exchange']),'usable'=>$integral['usable']- ($num * $soreType['exchange'])));
            $data = array(
                'sid'     => $sid,
                'uid'     => $id,
                'addtime' => time(),
                'content' => '兑换消费',
                'price'   => $num * $soreType['exchange'],
                'type'   => 1,
                'type2'   => 3,
            );
            db::name('tpbill')->insert($data);
        }

		//最后操作
		if ($arr) {
			db::name('tpexchange')->insert($arr);
			$this->data['status'] = 1;
			$this->data['msg'] = '兑换成功';
			return json($this->data);
		}else{
			$this->data['msg'] = '兑换失败';
			return json($this->data);
		}



	}

	// 获取用户代币列表
	public function getList(){
        $data = db::name('tpcoinlist')->field('id,pic,address,name,decimals as c_decimals')->where('status=1 AND is_default_show=1')->order('c_order desc')->select();

        foreach ($data as $key => $value) {
            $data[$key]['balance'] = 0;
        }

        $id = session('user.id');
        $r = db::name('usercoin')->where('uid='.$id)->find();

        if($r){
            $list = json_decode($r['coinlist'],TRUE);
            if(!empty($list)){
                $res = Db::name('tpcoinlist')->field('id,pic,address,name,decimals as c_decimals')
                    ->where('id','in',$list)->select();

                foreach ($res as $key => $value) {
                    $value['balance'] = 0;
                    $data[] = $value;
                }
            }

        }


        $address = input('address');
        $url = 'https://geth.168erp.cn/wallet/address/coins/'.$address.'/1';
        $file_contents = https_curl($url);

        $res = json_decode($file_contents,true);
        if($res['status'] == 1){
            foreach ($res['data'] as $k => $v) {
                foreach ($data as $key => $value) {

                    if($v['contract_addr'] == $value['address']){

                        $data[$key]['balance'] = $v['balance']==''?0:$v['balance'];
                        $data[$key]['c_decimals'] = $v['c_decimals'];

                    }
                }
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $data;
            return json($this->data);

        }

        $this->data['msg'] = '钱包地址错误';
        return json($this->data);

	}

	// 增加用户代币列表
	public function addList(){
		// $usercoin = db::name('usercoin');
		$cid = intval(get_input_data('cid'));
		if(!$cid){
			$this->data['msg'] = '币ID为空';
			return json($this->data);
		}

		$id = session('user.id');
		$r = db::name('usercoin')->where('uid='.$id)->find();

		if($r){
			$data['coinlist'] = json_decode($r['coinlist'],TRUE);
			if(!in_array($cid, $data['coinlist'])){
				$data['coinlist'][] = $cid;
			}
			$data['coinlist'] = json_encode($data['coinlist'],TRUE);	

			$res = db::name('usercoin')->where('id='.$r['id'])->update($data);
			if($res){
				$this->data['status'] = 1;
				$this->data['msg'] = '保存成功';
				return json($this->data);
			}
			$this->data['msg'] = '保存失败';
			return json($this->data);			
		}else{
			$data['uid'] = $id;
			$data['coinlist'][] = $cid;
			$data['coinlist'] = json_encode($data['coinlist'],TRUE);

			$res = db::name('usercoin')->insert($data);
			if($res){
				$this->data['status'] = 1;
				$this->data['msg'] = '添加成功';
				return json($this->data);
			}
			$this->data['msg'] = '添加失败';
			return json($this->data);
		}
	}	

	// 删除用户代币列表
	public function delList(){
		// $usercoin = db::name('usercoin');
		$cid = intval(get_input_data('cid'));
		if(!$cid){
			$this->data['msg'] = '币ID为空';
			return json($this->data);
		}
	
		$id = session('user.id');
		$r = db::name('usercoin')->where('uid='.$id)->find();
		if($r){
			$data['coinlist'] = json_decode($r['coinlist'],TRUE);
			foreach ($data['coinlist'] as $key => $value) {
				if($value == $cid){
					unset($data['coinlist'][$key]);
				}
			}

			$data['coinlist'] = json_encode($data['coinlist'],TRUE);	

			$res = db::name('usercoin')->where('id='.$r['id'])->update($data);
			if($res){
				$this->data['status'] = 1;
				$this->data['msg'] = '删除成功';
				return json($this->data);
			}
			$this->data['msg'] = '删除失败';
			return json($this->data);			
		}else{
			$this->data['msg'] = '删除失败';
			return json($this->data);
		}
	}

	// 搜索代币
	public function search(){
		$keyword = strtoupper(get_input_data('keyword'));
		$result = db::name('tpcoinlist')->field('id,pic,address,name')->where('name like "%'.$keyword.'%" AND is_default_show=0')->select();

        if(session('user.id')){
            $r = db::name('usercoin')->where('uid='.session('user.id'))->find();
            $list = json_decode($r['coinlist'],TRUE);
          
            if($list){
                foreach ($list as $key => $value) {
                    foreach ($result as $k =>$v) {
                        if($v['id'] == $value){
                            $flag = $k;
                        }
                    }
					
                    if(isset($flag)){                    
                        $result[$flag]['show'] = 1;
                    }
                }               
            }
          
        }

        foreach ($result as $key => $value) {
          
            if(!isset($value['show']) || $value['show'] != 1){
                $result[$key]['show'] = 0;
            }
       
        }

		if($result){
			$this->data['status'] = 1;
			$this->data['msg'] = '查询成功';
			$this->data['data'] = $result;
			return json($this->data);
		}
		$this->data['msg'] = '未找到'.$keyword.',请联系管理员';
		return json($this->data);
	}	

	   //搜索行情
 	public function research(){
		$coin = get_input_data('coin');

		if(!$coin){
			$this->data['msg'] = '请输入关键字';
			$this->data['status'] = 0;
			return json($this->data);				
		}

		$res = db::connect('mysql://root:f73172dbc6679878@47.75.75.134:3306/market#utf8')->name('mk_coins')->where('coin like "%'.$coin.'%" and status=1')->select();

		if($res){
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			$this->data['data'] = $res;
			return json($this->data);	
		}else{
			$this->data['msg'] = '未找到结果';
			$this->data['status'] = 0;
			return json($this->data);	
		}

	}

	//自定义添加
	public function setCoins(){
		$uid = session('user.id');
		$list = get_input_data('list');

		//检测更新还是添加
		$c = db::name('marketCoin')->where(array('uid'=>$uid))->find();
		if($c){
			db::name('marketCoin')->where(array('uid'=>$uid))->setField('list',$list);
		}else{
			db::name('marketCoin')->insert(array('uid'=>$uid,'list'=>$list));
		}

		//更新缓存
		cache('user_market_'.$uid,NULL);

		$this->data['status'] = 1;
		$this->data['msg'] = '保存成功';
		return json($this->data);

	}
	/**
	 * 获取代币
	 * @return [type] [description]
	 */
	public function getCoins(){
		$uid = session('user.id');
		$res = db::name('marketCoin')->where(array('uid'=>$uid))->find();

		if($res){
			$this->data['data'] = $res;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '暂无数据';
			$this->data['status'] = 0;
			return json($this->data);	
		}		
	}

	//获取自定义行情
	public function getMarket(){
		$uid = session('user.id');

		if(cache('user_market_'.$uid)){
			$this->data['data'] = cache('user_market_'.$uid);
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}

		$list = db::name('marketCoin')->where(array('uid'=>$uid))->value('list');
		$res = db::connect('mysql://root:f73172dbc6679878@47.75.75.134:3306/market#utf8')->table('mk_market')->where('id', 'in', $list)->order('addtime desc,id')->select();

		if($res){
			cache('user_market_'.$uid,$res,60);
			$this->data['data'] = $res;
			$this->data['msg'] = '获取成功';
			$this->data['status'] = 1;
			return json($this->data);
		}else{
			$this->data['msg'] = '暂无数据';
			$this->data['status'] = 0;
			return json($this->data);	
		}

	}

	/**
	 * 分享信息
	 * @return [type] [description]
	 */
	public function shareinfo(){
	    $uid = session('user.id');
	    $phone = session('user.phone');

	    $sum = db::name('tpbill')->where(['type'=>0,'type2'=>1,'uid'=>$uid])->sum('price');
	    $lv1 = db::name('tpuser')->where(['rtid'=>$phone])->count();
	    $lv2 = db::name('tpuser')->where(['rtid2'=>$phone])->count();
	    $data['uid'] = $uid;
	    $data['sum'] = $sum;
	    $data['lv1'] = $lv1;
	    $data['lv2'] = $lv2;

	    $this->data['msg'] = '获取成功';
	    $this->data['status'] = 1;
	    $this->data['data'] = $data;
	    return json($this->data);      
  	}
  	/**
  	 * 分享人数列表
  	 * @return [type] [description]
  	 */
  public function sharelist(){
    $uid = session('user.id');
    $p = get_input_data('p',1);
    $row = get_input_data('row',10);

    // $res = M('money2')->where('userid = '.$uid)->select();->buildSql()
    $res = db::name('tpbill')->alias('b')->join('ims_tpuser u', 'b.payee=u.phone', 'LEFT')->field('u.username as name,u.phone,b.addtime,b.price')->where('uid = '.$uid)->order('b.addtime desc')->limit(($p-1)*$row,$row)->select();
    
    if($res){
      $this->data['msg'] = '获取成功';
      $this->data['status'] = 1;
      $this->data['data'] = $res;
      return json($this->data);  
    }else{
      $this->data['msg'] = '暂无数据';
      return json($this->data);  
    }


  }

  //一级二级会员
  public function getMemberCount()
  {
  	$uid         = session('user.id');
  	$sid  		 = get_input_data('sid',-1);
	$row         = get_input_data('row', 10);
	// dump($uid);die;
    if(!$sid){
        $this->data['msg'] = '缺少参数sid';
        return json($this->data);
    }
	// $data['one'] = db::name('tpuser')->where('rtid='.$phone)->field('id')->select();

	$phone = db::name('tpuser')->field('id,phone')->where(['rtid'=>session('user.phone')])->buildSql();

	$one = db::name('tpbill')
        ->alias('b')
        ->join([$phone=> 'u'], 'u.id = b.payee')
        ->where('b.type=0 and b.type2=4 and b.sid='.$sid.' and b.uid='.$uid)
        ->field('b.id,b.uid,b.price,b.addtime,u.phone')
        ->order('b.addtime desc')
        ->select();

    $phone2 = db::name('tpuser')->field('id,phone')->where(['rtid2'=>session('user.phone')])->buildSql();

	$two = db::name('tpbill')
        ->alias('b')
        ->join([$phone2=> 'u'], 'u.id = b.payee')
        ->where('b.type=0 and b.type2=4 and b.sid='.$sid.' and b.uid='.$uid)
        ->field('b.id,b.uid,b.price,b.addtime,u.phone')
        ->order('b.addtime desc')
        ->select();

	$data['one'] = count($one); 
	$data['two'] = count($two); 
	$res = db::name('tpuser')->field('id,phone')->where('rtid ='.session('user.phone').' or rtid2='.session('user.phone'))->buildSql();

	$data['list'] = db::name('tpbill')
        ->alias('b')
        ->join([$res=> 'u'], 'u.id = b.payee')
        ->where('b.type=0 and b.type2=4 and b.sid='.$sid.' and b.uid='.$uid)
        ->field('b.id,b.uid,b.price,b.addtime,u.phone')
        ->order('b.addtime desc')
        ->limit($row)
        ->select();

    $data['sum'] = db::name('tpbill')
        ->alias('b')
        ->join([$res=> 'u'], 'u.id = b.payee')
        ->where('b.type=0 and b.type2=4 and b.sid='.$sid.' and b.uid='.$uid)
        ->order('b.addtime desc')
        ->sum('b.price');
    if($data){
      $this->data['msg'] = '获取成功';
      $this->data['status'] = 1;
      $this->data['data'] = $data;
      return json($this->data);  
    }else{
      $this->data['msg'] = '暂无数据';
      return json($this->data);  
    }

  }

  //推荐会员详情列表
  public function getCandylist()
  {
  	// $uid   = session('user.id');
  	$uid   = session('user.id');
  	$type  = get_input_data('type');
  	$sid  = get_input_data('sid',-1);
  	$p = get_input_data('p',1);
	$row = get_input_data('row',10);
	$mobile = get_input_data('phone');

  	if(!$type){
		$this->data['msg'] = 'type缺少参数';
		return json($this->data);
	}

	if(!$sid){
		$this->data['msg'] = 'sid缺少参数';
		return json($this->data);
	}
	$rtid  = 'rtid';

	if ($type != 1) {
		$rtid .= $type;
	}
	$where = '';
	if (isset($mobile)) {
        $where .= " and u.phone like '%$mobile%'";
    }
	$phone = db::name('tpuser')->field('id,phone')->where([$rtid=>session('user.phone')])->buildSql();

	$list = db::name('tpbill')
        ->alias('b')
        ->join([$phone=> 'u'], 'u.id = b.payee')
        ->where('b.type=0 and b.type2=4 and b.sid='.$sid.' and b.uid='.$uid)
        ->field('b.id,b.uid,b.price,b.addtime,u.phone')
        ->order('b.addtime desc')
        ->limit(($p-1)*$row.','.$row)
        ->select();

	$count =count($list);
	// $list = db::name('tpuser')->where($rtid.'='.$phone)->field('id,addtime,phone')->limit(->select();
	// echo $uid;exit;
    if($list){
      $this->data['msg'] = '获取成功';
      $this->data['status'] = 1;
      $this->data['data'] = ['list' => $list, 'total' => $count];
      return json($this->data);  
    }else{
      $this->data['msg'] = '暂无数据';
      return json($this->data);  
    }
	// where($rtid.'='.$phone)->select();
	
  }



/**
 * 会员资料
 * @return [type] [description]
 */
  public function getInfo()
  {
  	$this->data['msg'] = '获取成功';
    $this->data['status'] = 1;
    $this->data['data'] = session('user');
    return json($this->data);
  }




  public function setInfo(){
      $uid = session('user.id');
      $username = get_input_data('username');
      $pic = get_input_data('pic');

      if(isset($username)){
          $data['username'] = htmlspecialchars($username);
      }
      if(isset($pic)){
          $data['pic'] = htmlspecialchars($pic);
      }

      if(isset($data)){
          Db::name('tpuser')->where(['id'=>$uid])->update($data);
          session(NULL);
      }

      $this->data['msg'] = '修改成功';
      $this->data['status'] = 1;
      return json($this->data);
  }

  /**
   * 我发布的订单
   * @return [type] [description]
   */
  public function getc2c()
  {
		$type = get_input_data('type');
		if(!$type){
			$this->data['msg'] = "类型为空";
			return json($this->data);
		}
		$sid = get_input_data('sid/d',7);
		$p = get_input_data('p/d',1);
		$row = get_input_data('row/d',10);
		$uid = session('user.id');

		$sore = Db::name('tpsoretype')->where(['id'=>$sid])->find();
		if(!$sore){
			$this->data['msg'] = "币种不存在";
			return json($this->data);
		}

		$list = Db::name('tpc2c')
			->alias('a')
			->join('ims_tpuser b','a.uid=b.id')
			->where(['a.sid'=>$sid,'a.type'=>$type,'a.uid'=>$uid])
			->field('a.id,a.status,b.username,b.endpay,a.num,a.money,a.total_money,a.create_time,a.cid,a.update_time,a.enddate_time,a.action_time')
			->order('id desc')
			->limit(($p-1)*$row,$row)
			->select();
	    $total = Db::name('tpc2c')->where(['sid'=>$sid,'type'=>$type,'uid'=>$uid])->count();
		foreach ($list as $key => $value) {
			$list[$key]['create_time'] = getTimePassed($value['create_time']);
            $list[$key]['update_time'] = $list[$key]['update_time'] - time();
            $list[$key]['enddate_time'] = $list[$key]['enddate_time'] - time();
		}	
		if($list){
			$this->data['status'] = 1;
			$this->data['msg'] = "获取成功+".time();
			$this->data['data'] = ['total'=>$total,'list'=>$list];
		}else{
			$this->data['msg'] = "暂无数据";
		}
		return json($this->data);  	
  }

  /**
   * 我的订单
   * @return [type] [description]
   */
  public function getOrder()
  {
		$type = get_input_data('type');
		if(!$type){
			$this->data['msg'] = "类型为空";
			return json($this->data);
		}
		$sid = get_input_data('sid/d',7);
		$p = get_input_data('p/d',1);
		$row = get_input_data('row/d',10);
		$uid = session('user.id');

		$sore = Db::name('tpsoretype')->where(['id'=>$sid])->find();
		if(!$sore){
			$this->data['msg'] = "币种不存在";
			return json($this->data);
		}

		$list = Db::name('tpc2c')
			->where(['sid'=>$sid,'type'=>$type,'payid'=>$uid])
			->order('action_time desc')
			->limit(($p-1)*$row,$row)
			->select();
	    $total = Db::name('tpc2c')->where(['sid'=>$sid,'type'=>$type,'payid'=>$uid])->count();
		foreach ($list as $key => $value) {
			$list[$key]['create_time'] = getTimePassed($value['create_time']);
		}	
		if($list){
			$this->data['status'] = 1;
			$this->data['msg'] = "获取成功";
			$this->data['data'] = ['total'=>$total,'list'=>$list];
		}else{
			$this->data['msg'] = "暂无数据";
		}
		return json($this->data);  	
  }

    /**
     * 我的卖出订单
     * @return [type] [description]
     */
    public function getsell()
    {
        $type = get_input_data('type');
        if(!$type){
            $this->data['msg'] = "类型为空";
            return json($this->data);
        }
        $sid = get_input_data('sid/d',7);
        $p = get_input_data('p/d',1);
        $row = get_input_data('row/d',10);
        $uid = session('user.id');

        $sore = Db::name('tpsoretype')->where(['id'=>$sid])->find();
        if(!$sore){
            $this->data['msg'] = "币种不存在";
            return json($this->data);
        }

        $list = Db::name('tpc2c')
            ->alias('a')
            ->join('ims_tpuser b','a.uid=b.id')
            ->where(['a.sid'=>$sid,'a.type'=>$type,'a.uid'=>$uid])
            ->where('payid is not null')
            ->field('a.id,a.status,b.username,b.endpay,a.num,a.money,a.total_money,a.create_time,a.cid,a.update_time,a.enddate_time,a.action_time')
            ->order('action_time desc')
            ->limit(($p-1)*$row,$row)
            ->select();
        $total = Db::name('tpc2c')->where(['sid'=>$sid,'type'=>$type,'uid'=>$uid])->count();
        foreach ($list as $key => $value) {
            $list[$key]['create_time'] = getTimePassed($value['create_time']);
            $list[$key]['update_time'] = $list[$key]['update_time'] - time();
            $list[$key]['enddate_time'] = $list[$key]['enddate_time'] - time();
        }
        if($list){
            $this->data['status'] = 1;
            $this->data['msg'] = "获取成功+".time();
            $this->data['data'] = ['total'=>$total,'list'=>$list];
        }else{
            $this->data['msg'] = "暂无数据";
        }
        return json($this->data);
    }

	/**
	* 获取实名认证
	* @return [type] [description]
	*/
	public function getCard()
	{
		$uid = session('user.id');
		$list = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
		if($list){
			$this->data['status'] = 1;
			$this->data['msg'] = "获取成功";
			$this->data['data'] = $list;
		}else{
			$this->data['msg'] = "暂无数据";
		}	

		return json($this->data);	
	}

	/**
	* 修改实名认证
	* @return [type] [description]
	*/
	public function setCard()
	{
		$uid = session('user.id');
		$data['name'] = get_input_data('name');
		$data['idcar'] = get_input_data('idcar');
		$data['pic'] = get_input_data('pic');
        $data['pic1'] = get_input_data('pic1');
        $data['pic2'] = get_input_data('pic2');
		$data['status'] = 1;

		if(!$data['name']){
			$this->data['msg'] = "姓名为空";
			return json($this->data);
		}
		if(!$data['idcar']){
			$this->data['msg'] = "身份证号码为空";
			return json($this->data);
		}
		if(!$data['pic']){
			$this->data['msg'] = "手持身份证照片为空";
			return json($this->data);
		}
        if(!$data['pic1']){
            $this->data['msg'] = "身份正面照片为空";
            return json($this->data);
        }
        if(!$data['pic2']){
            $this->data['msg'] = "身份反面照片为空";
            return json($this->data);
        }

		$r = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
		//通过审核的不可修改
		if($r['status'] == 2){
			$this->data['msg'] = "不可修改";
			return json($this->data);
		}

		//检测身份证唯一
        $rr = Db::name('tpuserInfo')->where(['idcar'=>$data['idcar']])->find();
		if(!empty($rr) && $rr['uid'] != $uid){
            $this->data['msg'] = "身份证已存在,如果不是本人请联系管理员";
            return json($this->data);
        }

		if($r){
			Db::name('tpuserInfo')->where(['uid'=>$uid])->update($data);
		}else{
			$data['uid'] = $uid;
			Db::name('tpuserInfo')->insert($data);
		}

		$this->data['status'] = 1;
		$this->data['msg'] = "修改成功";
		return json($this->data);
	}

    /**
     * 每日生息流水
     */
	public function getbill(){
        $uid = session('user.id');
        $sid = get_input_data('sid');
        $p = get_input_data('p/d',1);
        $row = get_input_data('row/d',10);

        $list = Db::name('tpbill')->where(['sid'=>$sid,'type2'=>8,'type'=>0,'uid'=>$uid])->order('addtime desc')->limit(($p-1)*$row,$row)->select();
        $total = Db::name('tpbill')->where(['sid'=>$sid,'type2'=>8,'type'=>0,'uid'=>$uid])->count();
        if($list){
            $this->data['status'] = 1;
            $this->data['data'] = ['total'=>$total,'list'=>$list];
            $this->data['msg'] = "修改成功";
            return json($this->data);
        }else{
            $this->data['msg'] = "暂无数据";
            return json($this->data);
        }

    }

    /**
     * 获取公告
     */
    public function getNotice(){
        $uid = session('user.id');
        $sid = get_input_data('sid',7);
        $notice = Db::name('notice')->where(['sid'=>$sid,'is_personal'=>0])->order('id desc')->find();
        $uids = json_decode($notice['uids'],true);
        if(is_array($uids) && in_array($uid,$uids)){
            $this->data['msg'] = "暂无数据";
            return json($this->data);
        }else{
            $this->data['status'] = 1;
            $this->data['data'] = $notice;
            $this->data['msg'] = "获取成功";
            return json($this->data);
        }

    }
    /**
     * 获取公告时间
     */
    public function getNoticeTime(){
        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);
        $this->data['status'] = 1;
        $this->data['data'] = $arr['notice']['time'];
        $this->data['msg'] = "获取成功";
        return json($this->data);

    }


    /**
    *公告详情
     */
    public function getNoticeDet(){
        $uid = session('user.id');
        $id = get_input_data('id/d');
        $notice = Db::name('notice')->where(['id'=>$id])->find();

        if($notice){
            $uids = json_decode($notice['uids'],true);
           // dump($uids);die;
            if(!is_array($uids)||!in_array($uid,$uids)){
                $uids[] = $uid;
                Db::name('notice')->where(['id'=>$id])->update(['uids'=>json_encode($uids)]);
            }

            $this->data['status'] = 1;
            $this->data['data'] = $notice;
            $this->data['msg'] = "获取成功";
            return json($this->data);
        }else{
            $this->data['msg'] = "暂无数据";
            return json($this->data);
        }
    }

    /**
    *公告列表
     */
    public function getNoticeList(){
        $uid = session('user.id');
        $sid = get_input_data('sid/d',7);
        $p = get_input_data('p/d',1);
        $row = get_input_data('row/d',10);
        $where1['status'] =1;
        $where1['sid'] =$sid;
        $where1['is_personal'] =0;
        $where['is_personal'] =1;
        $where['uid'] =$uid;
        $list = Db::name('notice')->whereOr(function ($q)use($where1){
            $q->where($where1);
        })->whereOr(function ($q)use($where){
            $q->where($where);
        })->order('id desc')->limit(($p-1)*$row,$row)->select();
        $total = Db::name('notice')->where(['status'=>1,'sid'=>$sid])->count();
        if($list){
            foreach ($list as $key => $value){
                $arr = json_decode($value['uids'],true);
                if(is_array($arr) && in_array($uid,$arr)){
                    $list[$key]['see'] = 1;
                }else{
                    $list[$key]['see'] = 0;
                }
            }
            $this->data['status'] = 1;
            $this->data['data']['list'] = $list;
            $this->data['data']['total'] = $total;
            $this->data['msg'] = "获取成功";
            return json($this->data);
        }else{
            $this->data['msg'] = "暂无数据";
            return json($this->data);
        }

    }

    public function getSore(){
        $uid = session('user.id');
        $sid = get_input_data('sid/d');
        $data = Db::name('tpintegral')->where(['uid'=>$uid,'sid'=>$sid])->find();

        if($data){
            //用户等级
            $levels = Db::name('tprole')->order('min')->where(['type'=>1])->select();

            foreach ($levels as $key => $value){
                if($data['integral'] >= $value['min']){
                    $data['level'] = $value['rolename'];
                }
            }

            $this->data['status'] = 1;
            $this->data['data'] = $data;
            $this->data['msg'] = "获取成功";
            return json($this->data);
        }else{
            $data['dug_money'] = 0;
            $data['integral'] = 0;
            $data['usable'] = 0;
            $data['frozen'] = 0;
            $data['commission'] = 0;
            $data['all_bonus'] = 0;
            $data['level'] = '普通会员';

            $this->data['status'] = 1;
            $this->data['data'] = $data;
            $this->data['msg'] = "获取成功";
            return json($this->data);
        }

    }

    /**
     * 检测是否能赠送
     */
    public function check_give(){
        $uid = session('user.id');

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                $this->data['data'] = 2;
                return json($this->data);
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '允许交易';
            return json($this->data);

        }else{
            $this->data['msg'] = "请先实名认证";
            $this->data['data'] = 3;
            return json($this->data);
        }



    }

    /**
     * 检测是否能提现
     */
    public function check_pull(){
        $uid = session('user.id');

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                $this->data['data'] = 2;
                return json($this->data);
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '允许交易';
            return json($this->data);
        }else{
            $this->data['msg'] = "请先实名认证";
            $this->data['data'] = 3;
            return json($this->data);
        }

    }

    //一级二级好友列表
    public function getFriend(){
        $type=get_input_data('type');
        $p = get_input_data('p',1);
        $row = get_input_data('row',20);
        if($type==1){
            $where['rtid']=session('user.phone');
        }else{
            $where['rtid2']=session('user.phone');
        }
        $data['list'] = Db::name('tpuser')->field('addtime,phone,username')->where($where)->order('id desc')->limit(($p-1)*$row,$row)->select();
        $data['total'] = Db::name('tpuser')->where($where)->count();
        if ($data) {
            $this->data['status'] = 1;

            $this->data['msg'] = '获取成功';

            $this->data['data'] = $data;

            return json($this->data);
        }
    }

    //好友中心首页
    public function FriendCenter(){

        $row = get_input_data('row',20);

        $phone=session('user.phone');
        $where['rtid']= $where['rtid2']=$where1['rtid']=$where2['rtid2']=$phone;

        $data['list'] = Db::name('tpuser')->field('addtime,phone,username')->whereOr($where)->order('id desc')->limit($row)->select();
        $data['total1'] = Db::name('tpuser')->where($where1)->count();
        $data['total2'] = Db::name('tpuser')->where($where2)->count();
        if ($data) {
            $this->data['status'] = 1;

            $this->data['msg'] = '获取成功';

            $this->data['data'] = $data;

            return json($this->data);
        }
    }

    //购买记录
    public function buyList(){
        $phone = session('user.phone');
        $p = get_input_data('p/d',1);
        $row = get_input_data('row/d',10);
        $orders = new ordersModel();
        $goods = new goodsModel();
        $list = $orders->field('id,oid,title,paytime,state,total_money')->where(['phone'=>$phone,'state'=>3])->order('state desc,id desc')->limit(($p-1)*$row,$row)->select();

        if($list){
            foreach ($list as $key=>$value){
                $list[$key]['goods'] = $goods->field('name,money,num')->where(['oid'=>$value['id']])->select();
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data']['list'] = $list;
            $this->data['data']['total'] = $orders->field('id,oid,title,paytime,state,total_money')->where(['phone'=>$phone,'state'=>3])->count();
        }else{
            $this->data['msg'] = '暂无数据';
        }

        return json($this->data);
    }

    //用户默认收货地址
    public function addrdefault(){
        $uid = session('user.id');
        $aid = get_input_data('aid');

        if(!$aid){
            $this->data['msg'] = '缺少AID';
            return json($this->data);
        }
        $list = Db::name('address')->where(['id'=>$aid,'uid'=>$uid])->find();

        if($list){
            //设置默认
            Db::name('address')->where(['uid'=>$uid])->update(['isdefault'=>0]);
            Db::name('address')->where(['id'=>$aid])->update(['isdefault'=>1]);

            $this->data['status'] = 1;
            $this->data['msg'] = '设置成功';
        }else{
            $this->data['msg'] = '没有找到数据';
        }
        return json($this->data);
    }

    //获取单条地址
    public function addrget(){
        $uid = session('user.id');
        $aid = get_input_data('aid');

        if(!$aid){
            $this->data['msg'] = '缺少AID';
            return json($this->data);
        }
        $list = Db::name('address')->where(['id'=>$aid,'uid'=>$uid])->find();

        if($list){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $list;
        }else{
            $this->data['msg'] = '没有找到数据';
        }
        return json($this->data);
    }

    //用户收货地址列表
    public function addrlist(){
        $uid = session('user.id');
        $isdefault = get_input_data('isdefault');
        if($isdefault == 1){
            $where['isdefault'] = 1;
        }
        $where['uid'] = $uid;
        $list = Db::name('address')->where($where)->order('isdefault desc')->select();

        if($list){
            //拼接省市县
            foreach ($list as $key => $value){
                $xian = Db::name('area')->where(['id'=>$value['aid']])->find();//县
                $shi = Db::name('area')->where(['id'=>$xian['area_parent_id']])->find();//市
                $shen = Db::name('area')->where(['id'=>$shi['area_parent_id']])->find();//省

                $list[$key]['daddr'] = $shen['area_name'].$shi['area_name'].$xian['area_name'].$list[$key]['daddr'];
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data']['list'] = $list;
        }else{
            $this->data['msg'] = '暂无数据';
        }
        return json($this->data);
    }

    //新增收货地址
    public function addradd(){
        $data['uid'] = session('user.id');
        $data['name'] = get_input_data('name');
        $data['phone'] = get_input_data('phone');
        $data['aid'] = get_input_data('aid');
        $data['daddr'] = get_input_data('daddr');

        if(empty($data['name'])){
            $this->data['msg'] = '姓名为空';
            return json($this->data);
        }
        if(empty($data['phone'])){
            $this->data['msg'] = '联系方式为空';
            return json($this->data);
        }
        if(!preg_match("/^1\d{10}$/",$data['phone'])){
            $this->data['msg'] = '手机格式不正确';
            return json($this->data);
        }
//        if(empty($data['aid'])){
//            $this->data['msg'] = '请选择收货地址';
//            return json($this->data);
//        }
        if(empty($data['daddr'])){
            $this->data['msg'] = '请描述具体位置';
            return json($this->data);
        }

        $sum = Db::name('address')->where(['uid'=>$data['uid']])->count();
        if($sum >= 10){
            $this->data['msg'] = '您的数量已达到最大限制';
            return json($this->data);
        }
        $res = Db::name('address')->insertGetId($data);
        if($res){
            //设置成默认支付方式
            Db::name('address')->where(['uid'=>$data['uid']])->update(['isdefault'=>0]);
            Db::name('address')->where(['id'=>$res])->update(['isdefault'=>1]);
            $this->data['status'] = 1;
            $this->data['msg'] = '增加成功';
        }else{
            $this->data['msg'] = '系统繁忙,请稍后再试';
        }
        return json($this->data);
    }

    //编辑收货方式
    public function addredit(){
        $id = get_input_data('id');
        $uid = session('user.id');
        $data['name'] = get_input_data('name');
        $data['phone'] = get_input_data('phone');
        $data['aid'] = get_input_data('aid');
        $data['daddr'] = get_input_data('daddr');

        if(empty($id)){
            $this->data['msg'] = 'ID为空';
            return json($this->data);
        }
        if(empty($data['name'])){
            $this->data['msg'] = '姓名为空';
            return json($this->data);
        }
        if(empty($data['phone'])){
            $this->data['msg'] = '联系方式为空';
            return json($this->data);
        }
        if(!preg_match("/^1\d{10}$/",$data['phone'])){
            $this->data['msg'] = '手机格式不正确';
            return json($this->data);
        }
        if(empty($data['aid'])){
            $this->data['msg'] = '请选择收货地址';
            return json($this->data);
        }
        if(empty($data['daddr'])){
            $this->data['msg'] = '请描述具体位置';
            return json($this->data);
        }

        $res = Db::name('address')->where(['id'=>$id,'uid'=>$uid])->update($data);
        if($res){
            $this->data['status'] = 1;
            $this->data['msg'] = '修改成功';
        }else{
            $this->data['msg'] = '系统繁忙,请稍后再试';
        }
        return json($this->data);
    }

    //删除收货地址
    public function addrdel(){
        $id = get_input_data('id');
        $uid = session('user.id');
        if(empty($id)){
            $this->data['msg'] = 'ID为空';
            return json($this->data);
        }
        Db::name('address')->where(['id'=>$id,'uid'=>$uid])->delete();

        $this->data['status'] = 1;
        $this->data['msg'] = '删除成功';
        return json($this->data);

    }



}
