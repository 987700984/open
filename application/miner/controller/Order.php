<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2099 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\miner\controller;

use app\admin\controller\Base;;

use app\api\controller\Shop;
use app\orders\model\ordersModel;
use app\api\model\UserModel;
use app\api\model\UserAgent;

use think\Db;

class Order extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['user'] = ['like', '%' . $param['searchText'] . '%'];
            }
            // $where['status'] = 1;
            
        
            //无限级
            $selectResult = db::name('shop_orderform')->where($where)->limit($offset, $limit)->order('addtime desc')->select();
            // var_dump($selectResult);exit;
            $arr = array(0=> '', 1=>'运行中', '已过期');
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){
                    $str = '';
                    for ($i=0; $i < $vo['count'] ; $i++) { 
                            $str .= '&nbsp;&nbsp;&nbsp;';
                        }   
                    // $selectResult[$key]['title'] = $str.'|----'.$vo['title'];

                    $selectResult[$key]['zt'] = $arr[$vo['zt']];
                    $operate = [
                            '添加' => url('articleclass/articleclassAdd', ['pid' => $vo['id']]),
                            '编辑' => url('articleclass/articleclassEdit', ['id' => $vo['id']]),
                            '删除' => "javascript:articleclassDel('".$vo['id']."')"
                    ];              
                    // $selectResult[$key]['operate'] = showOperate($operate);             
                }               
                $return['total'] = db::name('shop_orderform')->where($where)->count();  //总数据
                $return['rows'] = $selectResult;

                return json($return);       
            }
        }
        $price  =  db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $miner = Db::name('miner')->where(['sid'=>7])->find();//配置参数
        $kjsl      = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl'); //全部矿机算力
        $quanwang = $kjsl+$miner['force']+$price*100;
        $this->assign('all_sl',$quanwang);
        $this->assign('user_sl',$kjsl);
        $this->assign('base_sl',$miner['force']);
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()){
            $param = input('post.');
            $param = parseParams($param['data']);
            if(!isset($param['uid'])){
                return json(['code'=>0,'msg'=>'缺少参数uid']);
            }
            if(!isset($param['goodsid'])){
                return json(['code'=>0,'msg'=>'缺少参数goodsid']);
            }
            $user = Db::name('tpuser')->where(['id'=>$param['uid']])->find();
            //下单
            $pay = $this->pay($user,$param['goodsid']);

            if($pay['code'] == 1){
                //设置成已付款
               preg_match('/out_trade_no%22%3A%22([A-Z0-9]*)%22/',$pay['data']['data'],$arr);
               $order = new ordersModel();
               $order->where(['oid'=>$arr[1]])->update(['state'=>2]);

               //发送矿机
               $result = $this->buy($param['uid'],$param['goodsid'],$arr[1]);
                $this->log->addLog($this->logData,'进行了手动发矿机操作');
               return $result;


            }

            return $pay;
        }
        $goods = Db::name('tpgoods')->field('goodsid,goodsname')->where(['cid'=>2])->select();
        $this->assign('goods',$goods);
        return $this->fetch();
    }

    public function search(){
        $search = input('search');
        if(!isset($search)){
            return json(['code'=>0,'msg'=>'缺少参数']);
        }
        $res = Db::name('tpuser')->field('id as uid,username as name,phone')->whereOr(['phone'=>$search,'id'=>$search])->find();

        if($res){
            return json(['code'=>1,'msg'=>'查询成功','data'=>$res]);
        }else{
            return json(['code'=>0,'msg'=>'未找到用户']);
        }
    }

    //支付接口
    private function pay($user,$goodsid){

        $goods = db::name('tpgoods')->where(['goodsid'=>$goodsid])->find();

        $count = db::name('shop_orderform')->where(['uid'=>$user['id'],'goodsid'=>$goodsid])->count();

        if ($goods['fjed'] <= $count) {
            return ['code'=>0,'msg'=>'你的'.$goods['goodsname'].'到达购买上限'];
        }

        $url = 'http://pay.168erp.cn/api/index/order/';
        if ($goods['cid'] == 2) {
            $goodslist = [
                ['name'=>$goods['goodsname'],'money'=>$goods['goodsprice'],'num'=>1]
            ];

            $price = $goods['goodsprice'];

            $arr = array(
                'pay'     	  => 'alipayapp',
                'title'    	  => $goods['goodsname'],
                'callback'	  => PAY_URL.'/api/shop/shop_buy?goodsid='.$goodsid,
                'name'    	  => $user['username'],
                'phone'   	  => $user['phone'],
                'address' 	  => 'dizhi',
//					'total_money' => $goods['goodsprice'],
                'total_money' => $price,
                'type' => 9,
                'goods'=> json_encode($goodslist)
            );

        }else{
            $goodslist = [
                ['name'=>$goods['goodsname'],'money'=>$goods['goodsprice'],'num'=>1]
            ];

            $price = $goods['goodsprice'];

            $arr = array(
                'pay'     	  => 'alipayapp',
                'title'    	  => $goods['goodsname'],
                'callback'	  => PAY_URL.'/api/shop/shop_agent_buy?goodsid='.$goodsid.'&token='.get_input_data('token'),
                'name'    	  => $user['username'],
                'phone'   	  => $user['phone'],
                'address' 	  => 'dizhi',
//                       'total_money' => $goods['goodsprice'],
                'total_money' => $price,
                'type' => 8,
                'goods'=> json_encode($goodslist)
            );
        }

        //$arr = json_encode($arr);
        $res =  mycurl($url, $arr, 1);
        $res = json_decode($res, true);

        return ['code'=>1,'msg'=>'下单成功','data'=>$res];
    }

    //购买
    private function buy($uid,$goodsid,$orderSn){

        $orders = new ordersModel();

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
                return ['code'=>0,'msg'=>'你的'.$goods['goodsname'].'到达购买上限'];
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
            $agent=$agentmodel->where(['uid'=>$uid])->find();
            $jifen->charge(['num' => $price, 'sid' => 7],$agent,$uid);
            return ['code'=>1,'msg'=>'添加成功'];
        }
        return ['code'=>0,'msg'=>'添加失败'];


    }

    public function edit(){
        $sl = input('sl/d');
        $res = Db::name('miner')->where(['sid'=>7])->update(['force'=>['exp','`force`+'.$sl]]);
        if($res){
            $this->log->addLog($this->logData,'修改基础算力');
            return ['code'=>1,'msg'=>'修改成功'];

        }
        return ['code'=>0,'msg'=>'修改失败'];
    }


}
