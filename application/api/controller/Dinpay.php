<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\Controller;
use app\goods\model\goodsModel;
use app\orders\model\ordersModel;
use app\api\model\dinpayModel;
use app\admin\model\UserModel;
use think\Db;

class Dinpay extends Controller
{
    public function b2c()
    {
        
        //获取商品信息//
        $goodsModel=new goodsModel();
        $goods=$goodsModel->getGoods();
        $goods2=$goods[1];$goods3=$goods[2];$goods4=$goods[3];
        $this->assign(['goods2'=>$goods2,'goods3'=>$goods3,'goods4'=>$goods4,'goods' => $goods, ]);
        
        //以下为智付签名信息//
        $merchant_private_key=config('dinpaykey')['merchant_private_key'];
        $merchant_code = config('dinpaykey')['merchant_code'];
        $service_type ="direct_pay";
        $interface_version ="V3.0";
        $sign_type ="RSA-S";   
        $input_charset = "UTF-8";
        $notify_url ="http://admin.2j1.com/api/dinpay/b2cNotify";
        $order_no = date( 'YmdHis' );
        $order_time = date( 'Y-m-d H:i:s' );
        $order_amount = $goods2['goodsprice'];
        $product_name =$goods2['goodsname'];
        //以下参数为可选参数，如有需要，可参考文档设定参数值//
        $return_url ="";
        $pay_type = "";        
        $redo_flag = "";        
        $product_code = "";       
        $product_desc = "";       
        $product_num = "";   
        $show_url = "";    
        $client_ip ="" ;   
        $bank_code = "";    
        $extend_param = "";   
        $extra_return_param = "";
        
        /*除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母*/
        $signStr= "";
        if($bank_code != ""){$signStr = $signStr."bank_code=".$bank_code."&";}
        if($client_ip != ""){$signStr = $signStr."client_ip=".$client_ip."&";}
        if($extend_param != ""){$signStr = $signStr."extend_param=".$extend_param."&";}
        if($extra_return_param != ""){$signStr = $signStr."extra_return_param=".$extra_return_param."&";}
        $signStr = $signStr."input_charset=".$input_charset."&";
        $signStr = $signStr."interface_version=".$interface_version."&";
        $signStr = $signStr."merchant_code=".$merchant_code."&";
        $signStr = $signStr."notify_url=".$notify_url."&";
        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&"; 
        if($pay_type != ""){$signStr = $signStr."pay_type=".$pay_type."&";}
        if($product_code != ""){$signStr = $signStr."product_code=".$product_code."&";}
        if($product_desc != ""){$signStr = $signStr."product_desc=".$product_desc."&";}  
        $signStr = $signStr."product_name=".$product_name."&";
        if($product_num != ""){$signStr = $signStr."product_num=".$product_num."&";}
        if($redo_flag != ""){$signStr = $signStr."redo_flag=".$redo_flag."&";}
        if($return_url != ""){$signStr = $signStr."return_url=".$return_url."&";}
        $signStr = $signStr."service_type=".$service_type;
        if($show_url != ""){$signStr = $signStr."&show_url=".$show_url;}
        
        $sign = "";
        $postdata=array(
            'sign'=>$sign,
            'merchant_code'=>$merchant_code,
            'bank_code'=>$bank_code,
            'order_no'=>$order_no,
            'order_amount'=>$order_amount,
            'service_type'=>$service_type,
            'input_charset'=>$input_charset,
            'notify_url'=>$notify_url,
            'interface_version'=>$interface_version,
            'sign_type'=>$sign_type,
            'order_time'=>$order_time,
            'product_name'=>$product_name,
            'client_ip'=>$client_ip,
            'extend_param'=>$extend_param,
            'extra_return_param'=>$extra_return_param,
            'pay_type'=>$pay_type,
            'product_code'=>$product_code,
            'product_desc'=>$product_desc,
            'product_num'=>$product_num,
            'return_url'=>$return_url,
            'show_url'=>$show_url,
            'redo_flag'=>$redo_flag,);
        $this->assign(['postdata'=>$postdata,]);
    	return $this->fetch();
    }
     
    public function b2cNotify()
    {   
    	if(request()->isPost()){
    	    /*获取post数据*/
    		if(!empty($_POST["merchant_code"])){$merchant_code=$_POST["merchant_code"];}else{$merchant_code="";}
    		if(!empty($_POST["interface_version"])){$interface_version = $_POST["interface_version"];}else{$interface_version ="";}
    		if(!empty($_POST["sign_type"])){$sign_type = $_POST["sign_type"];}else{$sign_type ="";}
    		if(!empty($_POST["sign"])){$dinpaySign = base64_decode($_POST["sign"]);}else{$dinpaySign ="";}
    		if(!empty($_POST["notify_type"])){$notify_type = $_POST["notify_type"];}else{$notify_type ="";}
    		if(!empty($_POST["notify_id"])){$notify_id = $_POST["notify_id"];}else{$notify_id="";}
    		if(!empty($_POST["order_no"])){$order_no = $_POST["order_no"];}else{$order_no ="";}
    		if(!empty($_POST["order_time"])){$order_time = $_POST["order_time"];}else{$order_time ="";}
    		if(!empty($_POST["order_amount"])){$order_amount = $_POST["order_amount"];}else{$order_amount ="";}
    		if(!empty($_POST["trade_status"])){$trade_status = $_POST["trade_status"];}else{$trade_status ="";}
    		if(!empty($_POST["trade_time"])){$trade_time = $_POST["trade_time"];}else{$trade_time ="";}
    		if(!empty($_POST["trade_no"])){$trade_no = $_POST["trade_no"];}else{$trade_no ="";}	
    		if(!empty($_POST["bank_seq_no"])){$bank_seq_no = $_POST["bank_seq_no"];}else{$bank_seq_no = "";}
    		if(!empty($_POST["extra_return_param"])){$extra_return_param = $_POST["extra_return_param"];}else{$extra_return_param ="";}

    		/*除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母*/
    		$signStr = "";
    		if($bank_seq_no != ""){$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";}
    		if($extra_return_param != ""){$signStr = $signStr."extra_return_param=".$extra_return_param."&";}
    		$signStr = $signStr."interface_version=".$interface_version."&";
    		$signStr = $signStr."merchant_code=".$merchant_code."&";
    		$signStr = $signStr."notify_id=".$notify_id."&";
    		$signStr = $signStr."notify_type=".$notify_type."&";
    		$signStr = $signStr."order_amount=".$order_amount."&";
    		$signStr = $signStr."order_no=".$order_no."&";
    		$signStr = $signStr."order_time=".$order_time."&";
    		$signStr = $signStr."trade_no=".$trade_no."&";
    		$signStr = $signStr."trade_status=".$trade_status."&";
    		$signStr = $signStr."trade_time=".$trade_time;
    		
    		/*RSA-S验证*/
    		$dinpay_public_key=config('dinpaykey')['dinpay_public_key'];
    		$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
    		$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);
    		
    		/*响应SUCCESS*/	
    		
    		if($flag){
    		    /*插入智付接口通知表*/
    		    $ims_tpdinpaynotify["order_no"]= $order_no;
    		    $ims_tpdinpaynotify["order_time"]= $order_time;
    		    $ims_tpdinpaynotify["order_amount"]= $order_amount;
    		    $ims_tpdinpaynotify["extra_return_param"]= $extra_return_param;
    		    $ims_tpdinpaynotify["trade_time"]= $trade_time;
    		    $ims_tpdinpaynotify["trade_status"]= $trade_status;
    		    $ims_tpdinpaynotify["bank_seq_no"]= $bank_seq_no;
    		    $ims_tpdinpaynotify["merchant_code"]= $merchant_code;
    		    $ims_tpdinpaynotify["notify_type"]= $notify_type;
    		    $ims_tpdinpaynotify["notify_id"]= $notify_id;
    		    $ims_tpdinpaynotify["interface_version"]= $interface_version;
    		    $ims_tpdinpaynotify["sign_type"]= $sign_type;
    		    $ims_tpdinpaynotify["sign"]= $signStr;
    		    $dinpayModel=new dinpayModel();
    		    $getOnenotify=$dinpayModel->getOnenotify($order_no);
    		    if(count($getOnenotify)<=0){
    		        $notify=$dinpayModel->insertnotify($ims_tpdinpaynotify);
    		    }else{
    		        $notify["code"]=2;
    		    }
    		       
    		    //插入成功则写入订单流水表//
    		    if($notify["code"]==1){
    		        
    		        //处理缓存信息-获取订单号
    		        $redis = new \Redis();
    		        $redis->connect('127.0.0.1', 6379);
    		        $goodsid=$redis->get($order_no);
    		        $redis->close();
    		        
    		        //根据userid获取id
    		        $userModel=new UserModel();
    		        $oneuser=$userModel->getOneUserByuserid($extra_return_param);
    		        $id=$oneuser["id"];
    		        
    		        //根据goodsid获取商品数量
    		        $goodsModel=new goodsModel();
    		        $onegoods=$goodsModel->getOneGoods($goodsid);
    		        $goodsnum=$onegoods['goodsnum'];
    		        $goodsnumgive=$onegoods['goodsnumgive'];
    		        if(empty($goodsnum)){
    		            $goodsnum=0;
    		        }
    		        if(empty($goodsnumgive)){
    		            $goodsnumgive=0;
    		        }
    		        $goodsSum=$goodsnum+$goodsnumgive;

    		        $ordersModel=new ordersModel();
    		        $ordersCon['goodsid']=$goodsid;
    		        $ordersCon['orderscreatetime']=date('Y-m-d H:i:s');
    		        $ordersCon['orderscreatepersonid']=$id;
    		        $ordersCon['ordersstatus']=1;
    		        $ordersCon['ordersquantity']=1;
    		        
    		        //选择代理商品套餐，写入代理订单类型，用于结算佣金
    		        if(in_array($goodsid, array("5","6","7","8","9"))){
    		            $ordersCon['orderstype']=3;
    		        }else{
    		            $ordersCon['orderstype']=0;
    		        }

    		        $ordersCon['forthwithgoodsprice']=$order_amount;
    		        $ordersCon['order_no']=$order_no;
    		        
    		        $getOneordersByorder_no=$ordersModel->getOneordersByorder_no($order_no);
    		        if(count($getOneordersByorder_no)<=0){
    		            $insertOrders=$ordersModel->insertorders($ordersCon);
    		        }else{
    		            $insertOrders['code']=1;
    		        }   		        

    		        //如果插入订单流水成功，则修改游戏服务器房卡数量  
    		        if($insertOrders['code']==0){
    		            $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';
    		            $result =  Db::connect($connection)->execute('update t_user set card_count=card_count+:card_count where userid=:userid ',['userid'=>$extra_return_param,'card_count'=>$goodsSum]);
    		            if($result > 0){
    		                
    		                //充值成功，则在用户房卡变动表t_user_card_change中插入一条日志信息。
    		                Db::connect($connection)->execute('insert into t_user_card_change(id,userid,source,card_num,create_time) values(uuid(),:userid,:source,:card_num,:create_time)',['userid'=>$extra_return_param,'source'=>'在线','card_num'=>$goodsSum,'create_time'=>date('Y-m-d H:i:s')]);                  
    		                //查询游戏服务器房卡数量
    		                $tuserReturn=Db::connect($connection)->query('select card_count from t_user where userid=:userid',['userid'=>$extra_return_param]);
    		                $cardnumReturn=$tuserReturn[0]['card_count'];

    		                
    		                
    		                //修改后台的房卡数量
    		                $inEditwithCardno['id']=$id;
    		                $inEditwithCardno['cardcount']=$cardnumReturn;
    		                $userModel->editUserCardcount($inEditwithCardno);
    		            }
    		        } 
    		    }
   
    		    $this->assign(['flag' => 'SUCCESS',]);
    		}else{
    		    $this->assign(['flag' => 'Verification Error',]);
    		}
    		
    		return $this->fetch();
    	}
    }
    
    public function b2cInitialize($goodsid)
    {
    	if(request()->isPost()){

    		$param = get_input_data();
    		$param = parseParams($param['data']);

    		//以下为智付签名信息//
    		$merchant_private_key=config('dinpaykey')['merchant_private_key'];
    		$merchant_code = config('dinpaykey')['merchant_code'];
    		$service_type ="direct_pay";
    		$interface_version ="V3.0";
    		$sign_type ="RSA-S";
    		$input_charset = "UTF-8";
    		$notify_url ="http://admin.2j1.com/api/dinpay/b2cNotify";
    		$order_no = $param['order_no'];
    		$order_time = $param['order_time'];
    		$order_amount = $param['order_amount'];
    		$product_name =$param['product_name'];
    		$userid=$param['extra_return_param'];

    		//处理缓存信息//
    		$redis = new \Redis();
    		$redis->connect('127.0.0.1', 6379);
    		$redis->set($order_no, $goodsid);
    		$redis->close();

    		//以下参数为可选参数，如有需要，可参考文档设定参数值//
    		$return_url ="";
    		$pay_type = "";
    		$redo_flag = "";
    		$product_code = "";
    		$product_desc = "";
    		$product_num = "";
    		$show_url = "";
    		$client_ip ="" ;
    		$bank_code = "";
    		$extend_param = "";
    		$extra_return_param = $param['extra_return_param'];//userid
    		
    		/*除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母*/
    		$signStr= "";
    		if($bank_code != ""){$signStr = $signStr."bank_code=".$bank_code."&";}
    		if($client_ip != ""){$signStr = $signStr."client_ip=".$client_ip."&";}
    		if($extend_param != ""){$signStr = $signStr."extend_param=".$extend_param."&";}
    		if($extra_return_param != ""){$signStr = $signStr."extra_return_param=".$extra_return_param."&";}
    		$signStr = $signStr."input_charset=".$input_charset."&";
    		$signStr = $signStr."interface_version=".$interface_version."&";
    		$signStr = $signStr."merchant_code=".$merchant_code."&";
    		$signStr = $signStr."notify_url=".$notify_url."&";
    		$signStr = $signStr."order_amount=".$order_amount."&";
    		$signStr = $signStr."order_no=".$order_no."&";
    		$signStr = $signStr."order_time=".$order_time."&";
    		if($pay_type != ""){$signStr = $signStr."pay_type=".$pay_type."&";}
    		if($product_code != ""){$signStr = $signStr."product_code=".$product_code."&";}
    		if($product_desc != ""){$signStr = $signStr."product_desc=".$product_desc."&";}
    		$signStr = $signStr."product_name=".$product_name."&";
    		if($product_num != ""){$signStr = $signStr."product_num=".$product_num."&";}
    		if($redo_flag != ""){$signStr = $signStr."redo_flag=".$redo_flag."&";}
    		if($return_url != ""){$signStr = $signStr."return_url=".$return_url."&";}
    		$signStr = $signStr."service_type=".$service_type;
    		if($show_url != ""){$signStr = $signStr."&show_url=".$show_url;}
    		
    		//获取sign值（RSA-S加密）//
    		$merchant_private_key= openssl_get_privatekey($merchant_private_key);
    		openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
    		$sign = base64_encode($sign_info);

    		$connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';
    		$tuserReturn=Db::connect($connection)->query('select userid from t_user where userid=:userid',['userid'=>$userid]);
    		if(count($tuserReturn)>0){
    		    return json(['sign' => $sign, 'data' => '0', 'msg' => '' ]);
    		}else{
    		    return json(['sign' => '', 'data' => '1', 'msg' => '' ]);
    		}

    	}
    } 
    public function test(){
        
        $a= in_array("1", array("0","2","3"));
        $this->assign(['flag' => '1','sessions' => dump($a) ]); 
        return $this->fetch();
    }
}
