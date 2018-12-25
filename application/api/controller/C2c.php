<?php
namespace app\api\controller;
use think\Db;
use think\Request;

class C2c extends Common
{
	/**
	 * 交易列表
	 * @return [type] [description]
	 */
	public function index()
	{
		
		$type = get_input_data('type');
		if(!$type){
			$this->data['msg'] = "类型为空";
			return json($this->data);
		}
		$sid = get_input_data('sid/d',-1);
		$p = get_input_data('p/d',1);
		$row = get_input_data('row/d',10);

		$sore = Db::name('tpsoretype')->where(['id'=>$sid])->find();
		if(!$sore){
			$this->data['msg'] = "币种不存在";
			return json($this->data);
		}

		$list = Db::name('tpc2c')
			->alias('a')
			->join('ims_tpuser b','a.uid=b.id')
			->where(['a.status'=>1,'a.sid'=>$sid,'a.type'=>$type])
			->field('a.id,a.cid,a.num,a.money,a.total_money,a.create_time,b.username,b.endpay,a.pay')
			->order('id desc')
			->limit(($p-1)*$row,$row)
            //->fetchSql(true)
			->select();

	    $total = Db::name('tpc2c')->where(['status'=>1,'sid'=>$sid,'type'=>$type])->count();
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
	 * 添加收款方式
	 */
	public function addAddr()
	{
		$uid  =session('user.id');
		$data['uid'] = $uid;
		$data['zhifu_name'] = get_input_data('zhifu_name');
		$data['zhifu_id'] = get_input_data('zhifu_id');
        $data['zhifu_code'] = get_input_data('zhifu_code');
		$data['yh_add'] = get_input_data('yh_add');
		$data['yh_id'] = get_input_data('yh_id');
		$data['yh_name'] = get_input_data('yh_name');
        $data['weixin_name'] = get_input_data('weixin_name');
        $data['weixin_id'] = get_input_data('weixin_id');
        $data['weixin_code'] = get_input_data('weixin_code');
		$data['phone'] = get_input_data('phone');

		$flag = false;//支付方式标志
		if($data['zhifu_name'] && $data['zhifu_id'] && $data['zhifu_code']){
            $flag = true;
		}
        if($data['yh_add'] && $data['yh_id'] && $data['yh_name']){
            $flag = true;
        }
        if($data['weixin_name'] && $data['weixin_id'] && $data['weixin_code']){
            $flag = true;
        }

        if(!$flag){
            $this->data['msg'] = "付款方式不完善";
            return json($this->data);
        }
		if(!$data['phone']){
			$this->data['msg'] = "联系方式为空";
			return json($this->data);
		}

		$id = Db::name('payInfo')->insertGetId($data);

		if($id){
			//设置成默认
			Db::name('tpuser')->where(['id'=>$uid])->update(['pay_info'=>$id]);
			//更新session
			session('user.pay_info',1);

			$this->data['status'] = 1;
			$this->data['msg'] = "添加成功";
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}

		return json($this->data);
	}

    /**
     * 获取收款方式
     * @return [type] [description]
     */
    public function getAddr()
    {
        $id = get_input_data('id/d');
        $uid = session('user.id');
        if(!$id){
            $this->data['msg'] = "ID为空";
            return json($this->data);
        }

        $data = Db::name('payInfo')->where(['id'=>$id,'uid'=>$uid])->find();

            if($data){
                $this->data['status'] = 1;
                $this->data['msg'] = "获取成功";
                $this->data['data'] = $data;
            }else{
                $this->data['msg'] = "暂无数据";
            }


        return json($this->data);
    }

    /**
     * 修改收款方式
     * @return [type] [description]
     */
    public function editAddr()
    {
        $id = get_input_data('id/d');
        $uid = session('user.id');
        if(!$id){
            $this->data['msg'] = "ID为空";
            return json($this->data);
        }

            $data['zhifu_name'] = get_input_data('zhifu_name');
            $data['zhifu_id'] = get_input_data('zhifu_id');
            $data['zhifu_code'] = get_input_data('zhifu_code');
            $data['yh_add'] = get_input_data('yh_add');
            $data['yh_id'] = get_input_data('yh_id');
            $data['yh_name'] = get_input_data('yh_name');
            $data['weixin_name'] = get_input_data('weixin_name');
            $data['weixin_id'] = get_input_data('weixin_id');
            $data['weixin_code'] = get_input_data('weixin_code');
            $data['phone'] = get_input_data('phone');

            $flag = false;//支付方式标志
            if($data['zhifu_name'] && $data['zhifu_id'] && $data['zhifu_code']){
                $flag = true;
            }
            if($data['yh_add'] && $data['yh_id'] && $data['yh_name']){
                $flag = true;
            }
            if($data['weixin_name'] && $data['weixin_id'] && $data['weixin_code']){
                $flag = true;
            }
            if(!$data['phone']){
                $this->data['msg'] = "联系方式为空";
                return json($this->data);
            }

            $id = Db::name('payInfo')->where(['id'=>$id,'uid'=>$uid])->update($data);
            if($id){
                $this->data['status'] = 1;
                $this->data['msg'] = "修改成功";
            }else{
                $this->data['msg'] = "服务器繁忙,请稍后再试";
            }

        return json($this->data);
    }


	/**
	 * 删除付款方式
	 * @return [type] [description]
	 */
	public function delAddr()
	{
		$id = get_input_data('id/d');
		$uid = session('user.id');
		if(!$id){
			$this->data['msg'] = "ID为空";
			return json($this->data);
		}	
		
		Db::name('payInfo')->where(['id'=>$id,'uid'=>$uid])->delete();
		$this->data['status'] = 1;
		$this->data['msg'] = "删除成功";			
		return json($this->data);				
	
	}

	/**
	 * 设置默认收款方式
	 * @return [type] [description]
	 */
	public function defAddr()
	{
		$id = get_input_data('id/d');
		$uid = session('user.id');
		if(!$id){
			$this->data['msg'] = "ID为空";
			return json($this->data);
		}

		Db::name('tpuser')->where(['id'=>$uid])->update(['pay_info'=>$id]);
		//更新session
        session('user.pay_info',$id);
		$this->data['status'] = 1;
		$this->data['msg'] = "设置成功";			
		return json($this->data);					
	}

	/**
	 * 支付方式列表
	 * @return [type] [description]
	 */
	public function listAddr()
	{
		$uid = session('user.id');	
		$list = Db::name('payInfo')->where(['uid'=>$uid])->select();
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
     * 检测是否有能交易
     */
    public function check(){
        $uid = session('user.id');

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

        //检测是否设置支付方式
        $user = Db::name('tpuser')->where(['id'=>$uid])->find();
        if(!$user['pay_info']){
            $this->data['msg'] = "请先配置收款信息";
            $this->data['data'] = 1;
            return json($this->data);
        }

        //检测是否关小黑屋
        if(!$user['ispay']){
            $this->data['msg'] = "该用户已关小黑屋，请联系管理员";
            $this->data['data'] = 4;
            return json($this->data);
        }

        $this->data['status'] = 1;
        $this->data['msg'] = '允许交易';
        return json($this->data);
    }

	/**
	 * 发布交易
	 */
	public function add()
	{

		$uid = session('user.id');
        //检测是否设置支付方式
        $user = Db::name('tpuser')->where(['id'=>$uid])->find();
        if(!$user['pay_info']){
            $this->data['msg'] = "请先配置收款信息";
            return json($this->data);
        }

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                return json($this->data);
            }
        }else{
            $this->data['msg'] = "请先实名认证";
            return json($this->data);
        }

        //检测是否关小黑屋
        if(!$user['ispay']){
            $this->data['msg'] = "该用户已关小黑屋，请联系管理员";
            return json($this->data);
        }

		$data['cid'] = date('YmdHms',time()).rand(10000,99999);
		$data['uid'] = $uid;
		$data['sid'] = get_input_data('sid/d');
		$data['num'] = abs(get_input_data('num/d'));
		//$data['money'] = abs(round(input('money'),2));
		$data['type'] = get_input_data('type');
		$data['status'] = 1;
		$data['create_time'] = time();
		$data['pay'] = get_input_data('pay');
	    $data['enddate_time'] = time()+172800;

		if(!$data['sid']){
			$this->data['msg'] = "币种ID为空";
			return json($this->data);
		}
		if(!$data['type']){
			$this->data['msg'] = "请选择发布类型";
			return json($this->data);
		}		
		$sore = Db::name('tpsoretype')->where(['id'=>$data['sid']])->find();
		if(!$sore){
			$this->data['msg'] = "币种不存在";
			return json($this->data);
		}
        $data['money'] = Db::name('market')->where(['coin'=>strtolower($sore['name'])])->order('id desc')->value('price');
		if(!$data['num']){
			$this->data['msg'] = "请输入数量";
			return json($this->data);
		}
		if($data['num'] < $sore['min_num']){
            $this->data['msg'] = "最低数量".$sore['min_num']."个";
            return json($this->data);
        }

        //手续费
        $fee = $data['num'] * $sore['proc'];
        if ($fee < $sore['min_proc']) {
            $fee = $sore['min_proc'];
        }
        if ($fee > $sore['max_proc']) {
            $fee = $sore['max_proc'];
        }

		if(!$data['money']){
			$this->data['msg'] = "请输入金额";
			return json($this->data);
		}
        if(!$data['pay']){
            $this->data['msg'] = "收款方式为空";
            return json($this->data);
        }
        if(!$data['enddate_time']){
            $this->data['msg'] = "结束时间为空";
            return json($this->data);
        }
        //检测数量
        if($data['type'] == 1){
            $integral = Db::name('tpintegral')->where(['sid'=>$data['sid'],'uid'=>$uid])->find();
//            //限额
//            if($data['num']>$integral['usable']){
//                $this->data['msg'] = "可用金额不足";
//                return json($this->data);
//            }

            if($data['num']+$fee > $integral['integral']){
                $this->data['msg'] = "金额不足";
                return json($this->data);
            }

            Db::name('tpintegral')->where(['id'=>$integral['id']])->update([
                'integral'=>$integral['integral']-$data['num']-$fee,
               // 'usable'=>$integral['usable']-$data['num'],
                'frozen'=>$integral['frozen']+$data['num']+$fee,
            ]);

            $data['fee'] = $fee;
        }

        $arr = explode(',',$data['pay']);
		$pay_info = Db::name('payInfo')->where(['id'=>session('user.pay_info')])->find();
        $data['phone'] = $pay_info['phone'];
        $data['name'] = session('user.username');
		if(in_array('wechat',$arr)){//微信
		    $data['weixin_name'] =$pay_info['weixin_name'];
            $data['weixin_id'] =$pay_info['weixin_id'];
            $data['weixin_code'] =$pay_info['weixin_code'];
        }
        if(in_array('alipay',$arr)){//支付宝
            $data['zhifu_name'] =$pay_info['zhifu_name'];
            $data['zhifu_id'] =$pay_info['zhifu_id'];
            $data['zhifu_code'] =$pay_info['zhifu_code'];
        }
        if(in_array('credit',$arr)){//银行卡
            $data['yh_name'] =$pay_info['yh_name'];
            $data['yh_id'] =$pay_info['yh_id'];
            $data['yh_add'] =$pay_info['yh_add'];
        }
		$data['total_money'] = $data['num']*$data['money'];

		$res = Db::name('tpc2c')->insertGetId($data);
		
		if($res){
		    //订单状态流水
            Db::name('tpc2cBill')->insert(['cid'=>$res,'uid'=>$uid,'time'=>time(),'content'=>'发布订单']);
            //糖果流水
            Db::name('tpbill')->insert([
                'sid'=>$data['sid'],
                'uid'=>$uid,
                'addtime'=>time(),
                'content'=>'c2c挂单冻结',
                'type'=>1,
                'price'=>$data['num']+$fee,
                'type2'=>5,
                'payee'=>0,
            ]);
			$this->data['status'] = 1;
			$this->data['msg'] = "发布成功";
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}

		return json($this->data);
	}

	/**
	 * 接单
	 * @return [type] [description]
	 */
	public function getOder()
	{
		$uid = session('user.id');

		//检测是否设置支付方式
        $user = Db::name('tpuser')->where(['id'=>$uid])->find();
        $pay_info = Db::name('payInfo')->where(['id'=>$user['pay_info']])->find();
		if(!$pay_info){
			$this->data['msg'] = "请先配置收款信息";
			return json($this->data);
		}

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                $this->data['msg'] = "实名认证未通过，请耐心等候或联系客服";
                return json($this->data);
            }
        }else{
            $this->data['msg'] = "请先实名认证";
            return json($this->data);
        }

		//检测是否关小黑屋
        if(!$user['ispay']){
            $this->data['msg'] = "该用户已关小黑屋，请联系管理员";
            return json($this->data);
        }
		$id = get_input_data('id/d');
		$odr = Db::name('tpc2c')->where(['id'=>$id,'status'=>1])->find();
		if(!$odr){
			$this->data['msg'] = "订单不存在";
			return json($this->data);
		}

		if($odr['uid'] == $uid){
            $this->data['msg'] = "不能接自己的单";
            return json($this->data);
        }
		$data['payid'] = $uid;
        $data['pay_phone'] = $pay_info['phone'];
        $data['pay_name'] = session('user.username');
		$data['update_time'] = time()+3600;
        $data['action_time'] = time();
		$data['status'] = 2;
		$res =  Db::name('tpc2c')->where(['id'=>$id,'status'=>1])->update($data);

		if($res){
            //订单状态流水
            Db::name('tpc2cBill')->insert(['cid'=>$id,'uid'=>$uid,'time'=>time(),'content'=>'已接单']);
			$this->data['status'] = 1;
			$this->data['msg'] = "接单成功";
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}
		return json($this->data);
	}

	public function tips(){
	    $uid = $uid = session('user.id');
	    $where = ['payid'=>$uid,'status'=>2];
	    $where1 = ['uid'=>$uid,'status'=>3];

        $num = Db::name('tpc2c')
            ->where(function($q) use($where){
                $q->where($where);
            })
            ->whereOr(function($q) use($where1){
            $q->where($where1);
        })->count();

        $this->data['status'] = 1;
        $this->data['msg'] = "查询成功";
        $this->data['data'] = $num;
        return json($this->data);

    }

	/**
	 * 付款
	 */
	public function pay1()
	{
		$id = get_input_data('id/d');
		$uid = session('user.id');
		$pic = get_input_data('pay_pic');

		if(!$id){
			$this->data['msg'] = "订单ID为空";
			return json($this->data);
		}		

		$res = Db::name('tpc2c')->where(['payid'=>$uid,'id'=>$id,'status'=>2])->update(['status'=>3,'update_time'=>time()+3600*24,'pay_pic'=>$pic]);

		if($res){
            //订单状态流水
            Db::name('tpc2cBill')->insert(['cid'=>$id,'uid'=>$uid,'time'=>time(),'content'=>'已付款']);
			$this->data['status'] = 1;
			$this->data['msg'] = "成功1";
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}
		return json($this->data);

	}

	/**
	 * 发币 确定收款
	 */
	public function pay2()
	{
		$id = get_input_data('id/d');
		$uid = session('user.id');

		if(!$id){
			$this->data['msg'] = "订单ID为空";
			return json($this->data);
		}		
        $oder = Db::name('tpc2c')->where(['uid'=>$uid,'id'=>$id,'status'=>3])->find();

		if($oder){
            Db::name('tpc2c')->where(['id'=>$id])->update(['status'=>4,'update_time'=>time()+3600]);
            //发币
            $payer = Db::name('tpintegral')->where(['uid'=>$oder['payid'],'sid'=>$oder['sid']])->find();
            if($payer){
                $r1 = Db::name('tpintegral')->where(['id'=>$payer['id']])->update(['integral'=>$payer['integral']+$oder['num']]);
            }else{
                $r1 = Db::name('tpintegral')->insert(['uid'=>$oder['payid'],'sid'=>$oder['sid'],'addtime'=>time(),'integral'=>$oder['num']]);
            }
            //清除冻结 扣除手续费

            $r2 = Db::name('tpintegral')->where(['uid'=>$oder['uid'],'sid'=>$oder['sid']])->update(['frozen'=>['exp','frozen-'.($oder['num']+$oder['fee'])]]);

            //记录流水
            if($r1){
                Db::name('tpbill')->insert([
                    'sid'=>$oder['sid'],
                    'uid'=>$oder['payid'],
                    'addtime'=>time(),
                    'content'=>'购买币',
                    'type'=>0,
                    'price'=>$oder['num'],
                    'type2'=>5,
                    'payee'=>$oder['uid'],
                    'ordersn'=>$oder['cid'],

                ]);
            }
            if($r2){
                Db::name('tpbill')->insert([
                    'sid'=>$oder['sid'],
                    'uid'=>$oder['uid'],
                    'addtime'=>time(),
                    'content'=>'出售币',
                    'type'=>1,
                    'price'=>$oder['num'],
                    'type2'=>5,
                    'payee'=>$oder['payid'],
                    'ordersn'=>$oder['cid'],
                    'proc'=>$oder['fee']

                ]);
            }
            //订单状态流水
            Db::name('tpc2cBill')->insert(['cid'=>$id,'uid'=>$uid,'time'=>time(),'content'=>'确认收款']);
            Db::name('tpc2cBill')->insert(['cid'=>$id,'uid'=>0,'time'=>time(),'content'=>'系统发糖果']);

            //更新累计完成单数
            Db::name('tpuser')->where(['id'=>$uid])->setInc('endpay');
            Db::name('tpuser')->where(['id'=>$oder['payid']])->setInc('endpay');

			$this->data['status'] = 1;
			$this->data['msg'] = "成功2";
			$this->pay3($oder['payid']);
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}
		return json($this->data);

	}	

	/**
	 * 交易完成  确定收币
	 * @return [type] [description]
	 */
	public function pay3($uid)
	{
		$id = get_input_data('id/d');
		//$uid = session('user.id');

		if(!$id){
			$this->data['msg'] = "订单ID为空";
			return json($this->data);
		}		
        $oder = Db::name('tpc2c')->where(['payid'=>$uid,'id'=>$id,'status'=>4])->find();
		$res = Db::name('tpc2c')->where(['id'=>$oder['id']])->update(['status'=>5,'update_time'=>time()]);

		if($res){
			//更新累计完成单数
			Db::name('tpuser')->where(['id'=>$uid])->setInc('endpay');
            Db::name('tpuser')->where(['id'=>$oder['payid']])->setInc('endpay');

            //订单状态流水
            Db::name('tpc2cBill')->insert(['cid'=>$id,'uid'=>0,'time'=>time(),'content'=>'交易完成']);

			$this->data['status'] = 1;
			$this->data['msg'] = "成功3";
		}else{
			$this->data['msg'] = "系统繁忙,请稍后再试";
		}
		return json($this->data);

	}

	/**
	 * 获取订单详情
	 * @return [type] [description]
	 */
	public function getC2cInfo()
	{
		$id = get_input_data('id/d');
		$uid = session('user.id');

		if(!$id){
			$this->data['msg'] = "订单ID为空";
			return json($this->data);
		}

		$odr = Db::name('tpc2c')->where(['id'=>$id])->find();
		$list = Db::name('tpc2cBill')->where(['cid'=>$id])->select();
		foreach($list as $key => $value){
            $list[$key]['time'] = getTimePassed($list[$key]['time']);
            if($uid == $value['uid']){
                $list[$key]['uid'] = '我';
            }elseif($value['uid'] == 0){
                $list[$key]['uid'] = '系统';
            }else{
                $list[$key]['uid'] = '对方';
            }
        }

		if(!$odr){
			$this->data['msg'] = "订单不存在";
			return json($this->data);
		}
		//订单所属
        if($uid != $odr['uid'] && $uid != $odr['payid']){
            $this->data['msg'] = "订单不存在";
            return json($this->data);
        }
        $odr['list'] = $list;
		$odr['update_time'] = $odr['update_time']-time();
		$this->data['status'] = 1;
		$this->data['msg'] = "获取成功";
		$this->data['data'] = $odr;
		return json($this->data);		
	}

    /**
     * 取消订单
     */
	public function endOder(){
        $id = get_input_data('id/d');
        if(!$id){
            $this->data['msg'] = "订单ID为空";
            return json($this->data);
        }
        $uid = session('user.id');
        $oder = Db::name('tpc2c')->where(['id'=>$id])->find();
        if($oder['uid'] != $uid && $oder['payid'] != $uid){
            $this->data['msg'] = "订单不存在";
            return json($this->data);
        }

        if($oder['uid'] == $uid){
            //设置订单状态为失败
            Db::name('tpc2c')->where(['id'=>$id])->update(['status'=>0]);
            //积分解冻
            Db::name('tpintegral')->where(['uid'=>$oder['uid'],'sid'=>$oder['sid']])->update(['frozen'=>['exp','frozen-'.($oder['num']+$oder['fee'])],'integral'=>['exp','integral+'.($oder['num']+$oder['fee'])]]);
            //糖果流水
            Db::name('tpbill')->insert([
                'sid'=>$oder['sid'],
                'uid'=>$uid,
                'addtime'=>time(),
                'content'=>'c2c挂单冻结释放',
                'type'=>0,
                'price'=>$oder['num']+$oder['fee'],
                'type2'=>5,
                'payee'=>0,
            ]);
        }

        if($oder['payid'] == $uid){
            //设置订单状态为失败
            Db::name('tpc2c')->where(['id'=>$id])->update(['status'=>1]);
        }

        /*
        //记录黑名单
        Db::name('blackList')->insert(['uid'=>$uid,'oid'=>$id,'time'=>time()]);
        */

        $this->data['status'] = 1;
        $this->data['msg'] = "取消成功";
        return json($this->data);
    }


}