<?php
namespace app\cardpay\controller;

use app\admin\controller\Base;
use think\Db;


class Cardpay extends Base
{

	//默认主页
    public function index()
    {  
        /* 
        $tpuserid = session('id');
        $imstpuserinfo = Db::name('tpuser')->where('id',$tpuserid)->find();
        $this->assign('cardcount',$imstpuserinfo['cardcount']);
        */

    	return $this->fetch();
    }
    
    //充值房卡
    public function cardpayAdd()
    {
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);

            $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';        
            $result =  Db::connect($connection)->execute('update t_user set card_count=card_count+:card_count where userid=:userid ',['userid'=>$param['userid'],'card_count'=>$param['card_count']]);    
            if($result > 0){
            	//充值成功，则在用户房卡变动表t_user_card_change中插入一条日志信息。
            	Db::connect($connection)->execute('insert into t_user_card_change(id,userid,source,card_num,create_time) values(uuid(),:userid,:source,:card_num,:create_time)',['userid'=>$param['userid'],'source'=>'后台','card_num'=>$param['card_count'],'create_time'=>date('Y-m-d H:i:s')]);
                return json(['code' => '0', 'data' => '', 'msg' => '']);}else{return json(['code' => '1', 'data' => '', 'msg' => '']);
            }
        }

        return $this->fetch();
    }   
}
