<?php

namespace app\api\controller;

use app\api\model\AgentLevel;
use app\orders\model\ordersModel;
use kdniao\kdniao;
use think\Request;
use app\admin\model\UserType;
use app\api\model\UserModel;
use app\api\model\UserAgent;

use think\Db;



class Shop extends Common{

	    //商品列表
        public function shop_list(){
		$p = get_input_data('p',1);
		$cid = get_input_data('cid');
		$row = get_input_data('row',20);
		if (!$cid) {
			$this->data['msg']    = '缺少CID参数';
			return json($this->data);	
		}
		$data['list'] = db::name('tpgoods')->where('cid='.$cid)->field('goodsid,roleid,goodsname,yxzq,fjed,kjsl,pic,goodsprice,month,agent_level')->limit(($p-1)*$row,$row)->order('goodsprice')->select();
		if($cid==2){
            $shouyi = 0.001421875;  //每小时收益
            $price   = db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
            $chanbi = $shouyi/$price;  //每M全网出币量

            foreach ($data['list'] as $k => $v){
                $data['list'][$k]['month'] =  floor($v['kjsl']*$chanbi*24*30*100)/100;
            }
        }
            if($cid==3){
                foreach ($data['list'] as $k => $v){
                    $level=AgentLevel::get(['sid'=>7,'level'=>$v['agent_level']]);
                    $data['list'][$k]['daili'] = [$level['commission1'],$level['commission2']];
                }
            }

		$data['total'] = db::name('tpgoods')->where('cid='.$cid)->count();
		if ($data) {
            $this->data['status'] = 1;

            $this->data['msg']	    = '获取成功';

			$this->data['data']   = $data;

			return json($this->data);
		}
	}

	//商城分类
	public function category(){
		$data['list'] = db::name('tpgoods_category')->order('orderdisplay')->select();
		if ($data) {
			$this->data['status'] = 1;

			$this->data['msg']    = '获取成功';

			$this->data['data']   = $data;

			return json($this->data);
		}else{
			$this->data['msg']    = '暂无数据';			

			return json($this->data);	
		}
	}

	//商品详情
	public function shop_find(){

		$goodsid = get_input_data('goodsid');
		if (!$goodsid) {
			$this->data['msg']    = '缺少ID参数';			

			return json($this->data);	
		}
		$goods   = db::name('tpgoods')->field('goodsid,goodsname,total,goodsprice,yxzq,kjsl,pic,month,is_virtual,cid,agent_level,content,pay_type')->where('goodsid='.$goodsid)->find();

		if ($goods) {
		    if($goods['cid']==2){
                $shouyi = 0.001421875;  //每小时收益
                $price   = db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
                $chanbi = $shouyi/$price;  //每M全网出币量

                $goods['month'] =   floor($goods['kjsl']*$chanbi*24*30*100)/100;
            }
            if($goods['cid']==3){

                    $level=AgentLevel::get(['sid'=>7,'level'=>$goods['agent_level']]);
                    $goods['daili'] = [$level['commission1'],$level['commission2'],$level['commission5'],$level['commission4']];

            }
            $goods['pay_type']=$goods['pay_type']?json_decode($goods['pay_type'],true):'';
			$this->data['status'] = 1;

			$this->data['msg']    = '获取成功';

			$this->data['data']   = ['list' => $goods];

			return json($this->data);
		}else{
			$this->data['msg']    = '暂无数据';			

			return json($this->data);
		}
	}

	//购买
	public function shop_buy(){
		$uid   = session('user.id');
		$goodsid    = input('goodsid');
        $orders = new ordersModel();
        $orderSn = get_input_data('out_trade_no');

		// dump(get_input_data());exit;
        $order = $orders->where(['oid'=>$orderSn])->find();
        if ($order['state'] == '待发货') {
        	
			$user  = db::name('tpuser')->where('id='.$uid)->find();
			$goods = db::name('tpgoods')->where('goodsid='.$goodsid)->find();
			// $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
			// $orderSn = $yCode[intval(date('Y')) - 2011] . date('d') . substr(time(), -5) . sprintf('%02d', rand(0, 7));
			$price=$goods['goodsprice'];
			$money=$user['price'];
			$count = db::name('shop_orderform')->where(['uid'=>$uid,'goodsid'=>$goodsid])->count();
			if ($goods['fjed'] <= $count) {
				$this->data['msg']    = '你的'.$goods['goodsname'].'到达购买上限';
				return json($this->data);
			}

			$zt  = db::name('tpuser')->where('id='.$uid)->update(['kjzt'=>1]);
			$map['user'] = $user['phone'];
			$map['uid'] = $uid;
			$map['project']=$goods['goodsname'];
			// $map['enproject']=$goods['enname'];
			$map['yxzq'] = $goods['yxzq'];
			$map['sumprice'] = $price;
			$map['addtime'] = time();
			$map['username']=$user['username'];
			$map['pic'] =$goods['pic'];
			$map['lixi']	= $goods['fjed'];
			// $map['qwsl'] = $goods['qwsl'];
			$map['kjsl'] = $goods['kjsl'];
			$map['kjbh'] = $orderSn;
			$map['goodsid'] = $goodsid;
			$map['sid'] = 7;
			$map['zt'] = 1;
			$map['uptime'] = time()+$goods['yxzq']*60*60;

			$id = db::name('shop_orderform')->insertGetId($map);
			db::name('tpgoods')->where('goodsid='.$goodsid)->setDec('total');
			$integral = db::name('tpintegral')->where('sid='.$map['sid'].' and uid='.$uid)->find();
			if (!$integral) {

				$data = array(
						'uid' 	  => $uid,
						'sid' 	  => $map['sid'],
						'addtime' => time(),
						'integral'=> 0
					);
				db::name('tpintegral')->insert($data);
			}
        	$order = $orders->where(['oid'=>$orderSn])->update(['state'=>3]);

			$jifen=new UserModel();
            $agentmodel=new UserAgent();
            $agent=$agentmodel->where(['uid'=>$uid,'sid'=>$map['sid']])->find();
            $jifen->charge(['num' => $price, 'sid' => 7],$agent,$uid);

			echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?id='.$orderSn.'&status=1"</script>';exit;
        }
			echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?status=0"</script>';

		


	}

	//用户矿机
	public function shop_order(){
		$p = get_input_data('p',1);
		$row = get_input_data('row',20);
		$phone   = session('user.phone');
		$uid   = session('user.id');

		$data['list'] = db::name('shop_orderform')->where(['uid'=>$uid,'zt'=>['>',0]])->limit(($p-1)*$row.','.$row)->order('addtime desc, zt desc')->select();

        $shouyi = 0.001421875;  //每小时收益
        $price   = db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $chanbi = $shouyi/$price;  //每M全网出币量

        foreach ($data['list'] as $k => $v){
            $data['list'][$k]['month'] =  floor($v['kjsl']*$chanbi*24*30*100)/100;
        }
        
		$data['total'] = db::name('shop_orderform')->where(['uid'=>$uid,'zt'=>['>',0]])->count();

		$data['kjsl'] = db::name('shop_orderform')->where(['uid'=>$uid,'zt'=>1])->sum('kjsl');
		$integral = db::name('tpintegral')->where('uid='.$uid.' and sid=7')->find();

		$data['dug_money'] = round($integral['dug_money'],6);
		$data['money'] = round($integral['money'],6);
		$state = db::name('shop_orderform')->where(['uid'=>$uid,'zt'=>1])->find();
		if ($state) {
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		if ($data) {
				
			$this->data['status'] = 1;
			$this->data['msg']    = '获取成功';
			$this->data['data']   = $data;


		}else{
			$this->data['msg']    = '暂无数据';

		}
		
		return json($this->data);
	}

	//用户矿机
	public function shop_order_find(){
		$id   = get_input_data('id');
		$result = db::name('shop_orderform')->where('id='.$id)->find();
		if ($result) {
				
			$this->data['status'] = 1;
			$this->data['msg']    = '获取成功';
			$this->data['data']   = $result;


		}else{
			$this->data['msg']    = '暂无数据';

		}
		
		return json($this->data);
	}

	public function shop_log(){
		$uid   = session('user.id');
		$p = get_input_data('p',1);
		$row = get_input_data('row',20);
		$data['list'] = db::name('shop_log')->where('uid='.$uid)->limit(($p-1)*$row.','.$row)->order('id desc')->select();
		$data['total'] = db::name('shop_log')->where('uid='.$uid)->count();
		foreach ($data['list'] as &$value) {
			$value['kjsl'] = round($value['kjsl'], 6);
			$value['money'] = round($value['money'], 6);
		}
		if ($data) {
				
			$this->data['status'] = 1;
			$this->data['msg']    = '获取成功';
			$this->data['data']   = $data;


		}else{
			$this->data['msg']    = '暂无数据';

		}
		
		return json($this->data);
	}

	public function shop_status(){
		$zt = get_input_data('status');
		$id = get_input_data('id');
		$data = db::name('shop_orderform')->where('id='.$id)->update(['zt'=>$zt]);
		if ($data) {
				
			$this->data['status'] = 1;
			$this->data['msg']    = '修改成功';
			$this->data['data']   = $data;


		}else{
			$this->data['msg']    = '修改失败';

		}
		return json($this->data);

	}

	public function shop_delete(){
		$id = get_input_data('id');
		$data = db::name('shop_orderform')->where('id='.$id.' and zt=2')->update(['zt'=>0]);
		if ($data) {
			$this->data['status'] = 1;
			$this->data['msg']    = '删除成功';
			$this->data['data']   = $data;

		}else{
			$this->data['msg']    = '删除失败';

		}
		return json($this->data);

	}

	  	//xiangou
  	public function shop_goods(){
      	$goodsid=get_input_data('goodsid');
      	if (!$goodsid) {
			$this->data['msg']    = '缺少goodsID参数';
			return json($this->data);
		}
		$user = session('user');
    	$goods = db::name('tpgoods')->where(['goodsid'=>$goodsid])->find();
        $count = db::name('shop_orderform')->where('goodsid='.$goodsid.' and uid='.$user['id'])->count();
      	if ($goods['fjed'] <= $count) {
				$this->data['msg']    = '你的'.$goods['goodsname'].'到达购买上限';			
				return json($this->data);	
      	}else{
      		$this->data['status'] = 1;
      		$this->data['msg']    = '正常';			
			return json($this->data);
      	}
    }

	//支付接口
	public function shop_pay(){

		$user = session('user');

		$goodsid=get_input_data('goodsid');
		$token = get_input_data('token');
		if(!$token){
            $this->data['msg']    = '缺少参数';
            return json($this->data);
        }
		if (!$goodsid) {
			$this->data['msg']    = '缺少goodsID参数';
			return json($this->data);
		}
		$goods = db::name('tpgoods')->where(['goodsid'=>$goodsid])->find();

        $count = db::name('shop_orderform')->where(['uid'=>$user['id'],'goodsid'=>$goodsid])->count();

        if ($goods['fjed'] <= $count) {
            $this->data['msg']    = '你的'.$goods['goodsname'].'到达购买上限';
            return json($this->data);
        }

        $url = PAY_URL.'/api/index/order/';

		if ($goods['cid'] == 2) {
			$goodslist = [
                ['name'=>$goods['goodsname'],'money'=>$goods['goodsprice'],'num'=>1]
            ];
//            if($user['id']==871567 || $user['id']==871568   ||   $user['id']==871569 || $user['id']==871570 || $user['id']==871571 || $user['id']==871572 || $user['id']==871573 || $user['id']==2520 || $user['id']==878021  ){
//                $price=0.01;
//            }else{
//                $price = $goods['goodsprice'];
//            }
			$arr = array(
					'pay'     	  => 'alipayapp',
					'title'    	  => $goods['goodsname'],
					'callback'	  => CALBACK.'/api/shop/shop_buy?goodsid='.$goodsid.'&token='.$token,
					'name'    	  => $user['username'],
					'phone'   	  => $user['phone'],
					'address' 	  => 'dizhi',
					'total_money' => $goods['goodsprice'],
//                    'total_money' => $price,
					'type' => 9,
                    'goods'=> json_encode($goodslist)
				);

		}else{
            $goodslist = [
                ['name'=>$goods['goodsname'],'money'=>$goods['goodsprice'],'num'=>1]
            ];
//            if($user['id']==871567 || $user['id']==871568   ||   $user['id']==871569 || $user['id']==871570 || $user['id']==871571 || $user['id']==871572 || $user['id']==871573 || $user['id']==820487 || $user['id']==878021  ){
//                $price=0.01;
//            }else{
//                $price = $goods['goodsprice'];
//            }
			$arr = array(
					'pay'     	  => 'alipayapp',
					'title'    	  => $goods['goodsname'],
					'callback'	  => CALBACK.'/api/shop/shop_agent_buy?goodsid='.$goodsid.'&token='.get_input_data('token'),
					'name'    	  => $user['username'],
					'phone'   	  => $user['phone'],
					'address' 	  => 'dizhi',
                    'total_money' => $goods['goodsprice'],
//                        'total_money' => $price,
					'type' => 8,
                    'goods'=> json_encode($goodslist)
				);
		}

		//$arr = json_encode($arr);
		$res =  mycurl($url, $arr, 1);
		$res = json_decode($res, true);

//		$res['data']['url'] = urldecode($res['data']['url']);
//		$a = https_curl($res['data']['url']);
		$this->data['status'] = 1;
		$this->data['msg']    = '获取成功';
		$this->data['data']   = $res['data'];
		return json($this->data);
	} 

	public function shop_one(){
		$oid = get_input_data('oid');
		if (!$oid) {
			$this->data['msg']    = '缺少ID参数';

			return json($this->data);	
		}
		$list = db::name('shop_orderform')->where(['kjbh'=>$oid])->field('kjbh,sumprice,project')->find();
		$this->data['status'] = 1;
		$this->data['msg']    = '获取成功';
		$this->data['data']   = $list;
		return json($this->data);
	}

    public function shop_two(){
        $orderSn = get_input_data('oid');
        if (!$orderSn) {
            $this->data['msg']    = '缺少ID参数';

            return json($this->data);
        }
        $orders = new ordersModel();

        $order = $orders->field('oid,title,total_money')->where(['oid'=>$orderSn])->find();
        $this->data['status'] = 1;
        $this->data['msg']    = '获取成功';
        $this->data['data']   = $order;
        return json($this->data);
    }

		//支付接口
	public function shop_agent_pay(){
		$user = session('user');

		$goodsid=get_input_data('goodsid');
		if (!$goodsid) {
			$this->data['msg']    = '缺少goodsID参数';

			return json($this->data);
		}
		$goods = db::name('tpgoods')->where(['goodsid'=>$goodsid])->find();
		$url = PAY_URL.'/api/index/order/';
		$arr = array(
				'pay'     	  => 'alipayapp',
				'title'    	  => $goods['goodsname'],
				'callback'	  => CALBACK.'/api/shop/shop_agent_buy?goodsid='.$goodsid.'&token='.get_input_data('token'),
				'name'    	  => $user['username'],
				'phone'   	  => $user['phone'],
				'address' 	  => 'dizhi',
				'total_money' =>  $goods['goodsprice'],
				'type' => 8,
			);
		//$arr = json_encode($arr);
		$res =  mycurl($url, $arr, 1);
		$res = json_decode($res, true);
		// $res['data']['url'] = urldecode($res['data']['url']);
//		$a = https_curl($res['data']['url']);
		$this->data['status'] = 1;
		$this->data['msg']    = '获取成功';
		$this->data['data']   = $res['data'];
		return json($this->data);
	} 

	//购买代理回调
	public function shop_agent_buy(){
		$uid   = session('user.id');
		$goodsid    = input('goodsid');
        $orders = new ordersModel();
        $orderSn = get_input_data('out_trade_no');

         $order = $orders->where(['oid'=>$orderSn])->find();
        // dump($order['state']);exit;
        if ($order['state'] == '待发货') {
        	
			$jifen=new UserModel();
            $agentmodel=new UserAgent();
			$goods = db::name('tpgoods')->where('goodsid='.$goodsid)->find();
            $order = $orders->where(['oid'=>$orderSn])->update(['state'=>3]);
            $agentmodel->buy_agent(['uid' => $uid, 'sid' => 7, 'level' => $goods['agent_level']]);

            $agent=$agentmodel->where(['uid'=>$uid, 'sid' => 7])->find();
            $jifen->charge(['num' => $goods['goodsprice'], 'sid' => 7],$agent,$uid);


			echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?id='.$orderSn.'&status=1"</script>';
        }
			echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?status=0"</script>';

		


	}


	public function limit(){
        $uid = session('user.id');
        $goodsid = get_input_data('goodsid/d');

        if (!$goodsid) {
            $this->data['msg']    = '缺少goodsID参数';
            return json($this->data);
        }

        $goods   = db::name('tpgoods')->where('goodsid='.$goodsid)->find();
        $count = db::name('shop_orderform')->where(['uid'=>$uid,'goodsid'=>$goodsid])->count();
        if ($goods['fjed'] <= $count) {
            $this->data['msg']    = '你的'.$goods['goodsname'].'到达购买上限';
        }else{
            $this->data['status'] = 1;
            $this->data['msg']    = '可以购买';
        }
        return json($this->data);
    }

    /**
     * 获取商品规格
     */
    public function get_spec()
    {
        $goods_id = get_input_data('goodsid');
        if(empty($goods_id)){
            $this->data['msg'] = '缺少商品ID';
            return json($this->data);
        }
        //商品规格 价钱 库存表 找出 所有 规格项id
        $keys = Db::name('tpgoodsPrice')->where("goodsid", $goods_id)->value("GROUP_CONCAT(`key` SEPARATOR ',')");
        $filter_spec = array();
        if ($keys) {
            $filter_spec2 = Db::name('tpspecItem')->alias('a')->join('ims_tpspec b','a.sid=b.sid','LEFT')->where('a.itemid','in',$keys)->select();
            foreach ($filter_spec2 as $key => $val) {
                $filter_spec[$val['spec_name']][] = array(
                    'itemid' => $val['itemid'],
                    'item_name' => $val['item_name'],
                );
            }
        }
        if(!empty($filter_spec)){
            $this->data['msg'] = '获取成功';
            $this->data['status'] = 1;
            $this->data['data'] = $filter_spec;
        }else{
            $this->data['msg'] = '该商品没有规格';
        }
        return json($this->data);
    }


    //获取规格 价格 库存
    public function get_spec_price(){
        $goodsid = get_input_data('goodsid');
        $spec = get_input_data('spec');
        if(empty($goodsid)){
            $this->data['msg'] = '缺少商品ID';
            return json($this->data);
        }
        if(empty($spec)){
            $this->data['msg'] = '缺少商品规格';
            return json($this->data);
        }

        $res = Db::name('tpgoodsPrice')->where(['goodsid'=>$goodsid,'key'=>$spec])->find();

        if($res){
            $this->data['msg'] = '获取成功';
            $this->data['status'] = 1;
            $this->data['data'] = $res;
        }else{
            $this->data['msg'] = '商品已下架';
        }

        return json($this->data);

    }

    //下单
    public function addorder(){
        $uid = session('user.id');
        $addrid = get_input_data('addrid');  //收款地址ID
        $goodsid = get_input_data('goodsid'); //商品ID
        $spec = get_input_data('spec');   //规格ID
        $sum = get_input_data('sum');  //商品数量
        $msg = get_input_data('msg');  //买家留言

        if(empty($goodsid)){
            $this->data['msg'] = '缺少商品ID';
            return json($this->data);
        }
        if(empty($sum)){
            $this->data['msg'] = '缺少数量';
            return json($this->data);
        }

        //开启事务
        Db::startTrans();
        try{
            $goods = Db::name('tpgoods')
                ->field('goodsid,goodsname,goodsprice as price,total,is_virtual,spec_item,cid')
                ->where(['goodsid'=>$goodsid])
                ->lock(true)
                ->find();

            if($goods['spec_item']){
                if(empty($spec)){
                    $this->data['msg'] = '缺少商品规格';
                    return json($this->data);
                }
                $goods_spec = Db::name('tpgoodsPrice')->where(['goodsid'=>$goodsid,'key'=>$spec])->find();
                $goods['price'] = $goods_spec['price'];
                $goods['key'] = $goods_spec['key'];
                $goods['total'] = $goods_spec['store_count'];
                $goods['id'] = $goods_spec['id'];
            }

            //实体必填收货方式
            if($goods['is_virtual']){
                if(empty($addrid)){
                    $this->data['msg'] = '缺少收货地址';
                    return json($this->data);
                }

                $address = Db::name('address')->where(['uid'=>$uid,'id'=>$addrid])->find();

                $xian = Db::name('area')->where(['id'=>$address['aid']])->find();//县
                $shi = Db::name('area')->where(['id'=>$xian['area_parent_id']])->find();//市
                $shen = Db::name('area')->where(['id'=>$shi['area_parent_id']])->find();//省

                $address['daddr'] = $shen['area_name'].$shi['area_name'].$xian['area_name'].$address['daddr'];

                if(empty($address)){
                    $this->data['msg'] = '未找到收货地址';
                    return json($this->data);
                }
            }else{
                $address['name'] = session('user.username');
                $address['phone'] = session('user.phone');
                $address['daddr'] = session('user.daddr');
            }

            if(empty($goods)){
                $this->data['msg'] = '商品已下架';
                return json($this->data);
            }
            if($goods['total'] <= 0){
                $this->data['msg'] = '商品已售完';
                return json($this->data);
            }
            if($goods['total'] < $sum){
                $this->data['msg'] = '库存不足';
                return json($this->data);
            }

            //减少库存
            if($goods['spec_item']){
                Db::name('tpgoodsPrice')->where(['id'=>$goods['id']])->setDec('store_count',$sum);
            }else{
                Db::name('tpgoods')->where(['goodsid'=>$goods['goodsid']])->setDec('total',$sum);
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->data['msg'] = '系统繁忙，请稍后再试';
            return json($this->data);
        }

        //添加订单
        $order['oid'] = chr(rand(65,90)).chr(rand(65,90)).date('YmdHms').rand(1000,9999); //20位订单号;
        $order['title'] = $goods['goodsname'];
        $order['uptime'] = time();
        $order['state'] = 1;
        $order['name'] = $address['name'];
        $order['phone'] = $address['phone'];
        $order['address'] = $address['daddr'];
        $order['total_money'] = $goods['price']*$sum;
        $order['type'] = $goods['cid'];
        $order['uid'] = $uid;
        $order['msg'] = $msg;

        //创建订单
        $orderid = Db::name('order')->insertGetId($order);
        //写入商品明细
        if($orderid){
            $goods_list['oid'] = $orderid;
            $goods_list['name'] = $goods['goodsname'];
            $goods_list['money'] = $goods['price'];
            $goods_list['num'] = $sum;
            $goods_list['goodsid'] = $goods['goodsid'];
            if(!empty($goods['key'])){
                $goods_list['key'] = $goods['key'];
            }
            Db::name('orderInfo')->insert($goods_list);

            $this->data['status'] = 1;
            $this->data['msg'] = '下单成功';
            $this->data['data']['oid'] = $order['oid'];
            $this->data['data']['orderid'] = $orderid;
            return json($this->data);
        }else{
            $this->data['msg'] = '系统繁忙，请稍后再试';
            return json($this->data);
        }

    }

    //取消订单
    public function delorder(){
        $uid = session('user.id');
        $orderid = get_input_data('orderid');
        $res = Db::name('order')->where(['id'=>$orderid,'uid'=>$uid,'state'=>1])->update(['state'=>0]);
        if($res){
            $this->data['status'] = 1;
            $this->data['msg'] = '取消成功';
            return json($this->data);
        }else{
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

    }

    //支付
    public function pay(){
        $orderid = get_input_data('orderid');
        $pay = get_input_data('pay');

        $order = Db::name('order')->where(['id'=>$orderid,'state'=>1])->find();
        if(!$order){
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

        //添加支付方式
        Db::name('order')->where(['id'=>$orderid,'state'=>1])->update(['pay'=>$pay]);

        switch ($pay)
        {
            case 'alipayapp':
                    $pay_back = $this->api_pay($order);
                break;
            case 'btkj':
                    $pay_back = $this->btjz_pay($order);
                 break;
            case 'eth':

                break;
            default:

        }

    }

    //支付接口
    public function api_pay(){
        $orderid = get_input_data('orderid');
        $pay = get_input_data('pay');
        if(!$pay){
            $this->data['msg'] = '选择支付方式';
            return json($this->data);
        }
        $order = Db::name('order')->where(['id'=>$orderid,'state'=>1])->find();

        if(!$order){
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

        $url = PAY_URL.'/api/index/order/';
        $arr = array(
            'pay'     	  => $pay,
            'oid'         => $order['oid'],
            'title'    	  => $order['title'],
            'callback'	  => CALBACK.'/api/shop/pay_notify?oid='.$order['oid'],
            'name'    	  => $order['name'],
            'phone'   	  => $order['phone'],
            'address' 	  => $order['address'],
            'total_money' => $order['total_money'],
            'type' => 8,
        );

        $res =  mycurl($url, $arr, 1);
        $res = json_decode($res, true);

        if($res['code'] == 200){
            Db::name('order')->where(['id'=>$orderid])->update(['pay'=>$pay]);
            $this->data['status'] = 1;
            $this->data['msg']    = '获取成功';
            $this->data['data']   = $res['data'];
            return json($this->data);
        }else{
            $this->data['msg'] = '系统繁忙，请稍后再试';
            return json($this->data);
        }

    }

    public function btjz_pay(){
        $uid = session('user.id');
        $orderid = get_input_data('orderid');
        $password = get_input_data('password');

        $order = Db::name('order')->where(['id'=>$orderid,'state'=>1])->find();
        if(!$order){
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

        if(MD5($password) != session('user.password')){
            $this->data['msg'] = '密码错误';
            return json($this->data);
        }

        $price   = Db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $integral = Db::name('tpintegral')->where(['uid'=>$uid,'sid'=>7])->find();

        $btjz = $order['total_money']/$price;

        if($integral['integral'] < $btjz){
            $this->data['msg'] = 'BTJZ数量不足';
            return json($this->data);
        }

        //开启事务
        Db::startTrans();
        try{

            //减币
            Db::name('tpintegral')->where(['uid'=>$uid,'sid'=>7])->setDec('integral',$btjz);
            //加流水
            Db::name('tpbill')->insert(['sid'=>7,'uid'=>$uid,'addtime'=>time(),'content'=>'购买'.$order['name'],'type'=>1,'price'=>$btjz,'type2'=>5,'ordersn'=>$order['oid']]);
            //改变订单状态
            Db::name('order')->where(['id'=>$order['id']])->setInc('state');

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->data['msg'] = '系统繁忙，请稍后再试';
            return json($this->data);
        }

        $this->data['status'] = 1;
        $this->data['msg'] = '支付成功';
        return json($this->data);


    }

    //余额支付
    public function money_pay(){
        $uid = session('user.id');
        $orderid = get_input_data('orderid');
        $password = get_input_data('password');
        $order = Db::name('order')->where(['id'=>$orderid,'state'=>1])->find();
        if(!$order){
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

        if(MD5($password) != session('user.password')){
            $this->data['msg'] = '密码错误';
            return json($this->data);
        }

        $agent=UserAgent::get(['sid'=>7,'uid'=>$uid]);
        $btjz =$agent?$agent['money']:0;

        if($order['total_money'] > $btjz){
            $this->data['msg'] = '余额不足';
            return json($this->data);
        }

        //开启事务
        Db::startTrans();
        try{

            //减余额
            $agent->money-=$order['total_money'];
            $agent->save();
            //加流水
            Db::name('money_log')->insert(['sid'=>7,'uid'=>$uid,'addtime'=>time(),'content'=>'购买商品','type'=>1,'type2'=>2,'type3'=>6,'agent_price'=>$order['total_money']]);
            //改变订单状态
            Db::name('order')->where(['id'=>$order['id']])->setInc('state');

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->data['msg'] = '系统繁忙，请稍后再试';
            return json($this->data);
        }

        $this->data['status'] = 1;
        $this->data['msg'] = '支付成功';
        return json($this->data);


    }

    //ETH支付
    public function eth_pay(){
        $res = https_curl('https://api.feixiaohao.com/site/headercoinlist');
        $res = json_decode($res,true);
        $content = json_decode($res['content'],true);
        foreach($content as $key => $value){
            if($value['Title'] == 'ETH'){
                $price = $value['Price_Cny'];
            }
        }

        if(!$price){
            $this->data['msg'] = '意料之外的错误,请联系客服';
            return json($this->data);
        }

        //$token = get_input_data('token');
        $orderid = get_input_data('orderid');
        $address = get_input_data('address');
        $password = get_input_data('password');
        $gasLimit = get_input_data('gasLimit');
        $gasPrice = get_input_data('gasPrice');
       // $contractAddr = get_input_data('contractAddr');

        $order = Db::name('order')->where(['id'=>$orderid,'state'=>1])->find();
        if(!$order){
            $this->data['msg'] = '订单不存在';
            return json($this->data);
        }

        $pram['token'] = 'ETH';
        $pram['ordId'] = $order['oid'];
        $pram['fromAccount'] = $address;
        $pram['pwd'] = $password;
        $pram['value'] = $order['total_money']/$price;
        $pram['gasLimit'] = $gasLimit;
        $pram['gasPrice'] = $gasPrice;
        $pram['contractAddr'] = '';

        $result = mycurl('http://open.demo.com/api/dbug/dd', $pram,1);
        dump($result);

    }

    //处理订单
    private function notify($oid){
        $model = new \app\shop\model\Order();
        $res = $model->where(['oid'=>$oid,'state'=>1])->find();

        if(!$res){
            $data['status'] = 0;
            $data['msg'] = '订单异常';
            return $data;
        }
        //变成已付款
        $res->save(['state'=>2]);
        $goodsid = $res->orderinfo()->find();

        $goods = Db::name('tpgoods')->where(['goodsid'=>$goodsid['goodsid']])->find();
        //判断类型
        if($res['type'] == 2){ //矿机
            Db::name('tpuser')->where('id='.$res['uid'])->update(['kjzt'=>1]); //改变用户矿机界面
            $user = Db::name('tpuser')->where(['id'=>$res['uid']])->find();

            $map['user'] = $user['phone'];
            $map['uid'] = $user['id'];
            $map['project']=$goods['goodsname'];
            $map['yxzq'] = $goods['yxzq'];
            $map['sumprice'] = $goods['goodsprice'];
            $map['addtime'] = time();
            $map['username']=$user['username'];
            $map['pic'] =$goods['pic'];
            $map['lixi']	= $goods['fjed'];
            // $map['qwsl'] = $goods['qwsl'];
            $map['kjsl'] = $goods['kjsl'];
            $map['kjbh'] = $res['oid'];
            $map['goodsid'] = $goodsid['goodsid'];
            $map['sid'] = 7;
            $map['zt'] = 1;
            $map['uptime'] = time()+$goods['yxzq']*60*60;

            for($i=0;$i<$goodsid['num'];$i++){
                Db::name('shop_orderform')->insert($map);
            }

            Db::name('tpgoods')->where('goodsid='.$goodsid['goodsid'])->setDec('total',$goodsid['num']);
            $integral = Db::name('tpintegral')->where('sid='.$map['sid'].' and uid='.$user['id'])->find();
            if (!$integral) {
                $data = array(
                    'uid' 	  => $user['id'],
                    'sid' 	  => $map['sid'],
                    'addtime' => time(),
                    'integral'=> 0
                );
                Db::name('tpintegral')->insert($data);
            }
            //变成已发货
            Db::name('order')->where(['id'=>$res['id']])->setInc('state');

            $jifen=new UserModel();
            $agentmodel=new UserAgent();
            $agent=$agentmodel->where(['uid'=>$user['id'],'sid'=>$map['sid']])->find();
            $jifen->charge(['num' => $goods['goodsprice'], 'sid' => 7],$agent,$user['id']);

        }elseif($res['type'] == 4){  //代理
            $jifen=new UserModel();
            $agentmodel=new UserAgent();

            //变成已发货
            Db::name('order')->where(['id'=>$res['id']])->setInc('state');

            $agentmodel->buy_agent(['uid' => $res['uid'], 'sid' => 7, 'level' => $goods['agent_level']]);
            $agent=$agentmodel->where(['uid'=>$res['uid'], 'sid' => 7])->find();
            $jifen->charge(['num' => $goods['goodsprice'], 'sid' => 7],$agent,$res['uid']);
        }else{
            //判断是否需要返佣
            if($goods['iscommission'] == 1){
                $jifen=new UserModel();
                $agentmodel=new UserAgent();
                $agent=$agentmodel->where(['uid'=>$res['uid'],'sid'=>7])->find();
                $jifen->charge(['num' => $goods['goodsprice'], 'sid' => 7],$agent,$res['uid']);
            }
        }

        $data['status'] = 1;
        $data['msg'] = '修改成功';
        return $data;
    }

    //ETH回调
    public function eth_notify(){

    }

    //支付回调
    public function pay_notify(){
        $oid = get_input_data('oid');

        //检测远程订单
        $model = new \app\orders\model\ordersModel();
        $od = $model->where(['oid'=>$oid,'state'=>2])->find();

        if(!$od){
            echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?status=0"</script>';
            exit();
        }else{
            //处理订单
            $res = $this->notify($oid);
            if($res['status'] == 0){
                echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?status=0"</script>';
                exit();
            }

            echo '<script>location.href="http://h5.wetoken.vip/buyStatus.html?id='.$oid.'&status=1"</script>';
            exit();
        }

    }


    //快递查询
    public function kd(){
        $uid = session('user.id');
        $orderid = get_input_data('orderid');
        $model = new \app\shop\model\Order();
        $order = $model->where(['id'=>$orderid,'uid'=>$uid,'state'=>['in','3,4']])->find();
        if(!$order){
            $this->data['msg'] = '未找到相关信息';
            return json($this->data);
        }

        $kd = new kdniao();
        $res = $kd->getOrderTracesByJson($order['company'],$order['courier']);

        $res['data']['courier'] = $order['courier'];
        $res['data']['kuaidi'] = $order['kuaidi'];

        return json($res);

    }


}
