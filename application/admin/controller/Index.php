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
namespace app\admin\controller;
use app\admin\model\MoneyLog;
use app\shop\model\Order;
use app\shop\model\TpgoodsType;
use app\shop\model\Tpspec;
use \think\Db;
use app\orders\model\ordersModel;

class Index extends Base
{
    public function index()
    {
       return $this->fetch('/index');
    }

    /**
     * 后台默认首页
     * @return mixed
     */
    public function indexPage()
    {
        if (session('soretype')) {
          return '';
        }
//        return '';
        $today = strtotime(date('Y-m-d',time()).' 00:00:00');

        //用户统计
        $user = Db::name('tpuser');
        $user_all = $user->count(); //当前用户总数
        $user_today_reg = $user->where('addtime >'.$today)->count();
        $this->assign('user_all',$user_all);
        $this->assign('user_today_reg',$user_today_reg);

        //币总数
        $money = db::name('tpintegral')->sum('money');
        $this->assign('money',$money);
        
//        //代理统计
//        $agent = Db::name('tpagent_apply');
//        $agent_all = $agent->count(); //当前代理总数
//        $agent_ing = $agent->where('status=0')->count(); //待审核
//        $agent_end = $agent->where('status=1')->count(); //审核通过
//        $agent_bad = $agent->where('status=2')->count(); //审核不通过
//        $this->assign('agent_all',$agent_all);
//        $this->assign('agent_ing',$agent_ing);
//        $this->assign('agent_end',$agent_end);
//        $this->assign('agent_bad',$agent_bad);




        return $this->fetch('index');
    }

    //分类联动数据
    public function get_goods_type(){
        if(request()->isPost()){
            $cid=input('cid');
            $model=new TpgoodsType();
            $list=$model->where('cid',$cid)->select();
            return $list;

        }
    }

    //模型联动数据
    public function get_spec(){
        if(request()->isPost()){
            $tid=input('tid');
            $model=new Tpspec();
            $list=$model->where('tid',$tid)->select();
            $lis=[];
            if($list){

                foreach ($list as $k=>$v){

                    $l=$model->spec_item()->where('sid',$v['sid'])->select();
                    if($l){
                        $lis[$k]['spec']=$v['spec_name'];
                        $lis[$k]['spec_item']= $l;
                    }

                }
            }
            return $lis;

        }
    }

    //订单统计
    public function dingdan(){
        if($this->request->isAjax()){
            $model=new Order();
            $data=$this->request->post();
            $key=$quxiao=$daifu =$yifu=$yifa=$yishou=$list=[];
            if($data['type']==3 && $data['month']==1){
                $data['month']=date('Y');
                for ( $i=1;$i<13;$i++){
                    $i1=$i;
                    if(strlen($i)==1){
                        $i1='0'.$i;
                    }

                    $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                    $begin1=$data['month'].$i1.'01 23:59:59';
                    $last=strtotime($begin1." +1 month -1 day");

                    $where['uptime']=['between',[$begin,$last]];
                    $where1['uptime']=['between',[$begin,$last]];
                    $key[]=$i1.'月';
                    $quxiao[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>0])->count();
                    $daifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>1])->count();
                    $yifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>2])->count();
                    $yifa[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>3])->count();
                    $yishou[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>4])->count();

                }
            }else{
                switch ($data['type']){
                    case 1 :
                        for ( $i=0;$i<24;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].' '.$i1.':00:00');
                            $last=strtotime( $data['month'].' '.$i1.':59:59');
                            $key[]=$i1.'时';
                            $quxiao[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>0])->count();
                            $daifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>1])->count();
                            $yifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>2])->count();
                            $yifa[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>3])->count();
                            $yishou[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>4])->count();

                        }
                        break;
                    case 2 :

                        $len=date('t', strtotime($data['month'].'-01 00:00:00'));
                        for ( $i=1;$i<=$len;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].'-'.$i1.' 00:00:00');
                            $last=strtotime( $data['month'].'-'.$i1.' 23:59:59');
                            $key[]=$i1;
                            $quxiao[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>0])->count();
                            $daifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>1])->count();
                            $yifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>2])->count();
                            $yifa[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>3])->count();
                            $yishou[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>4])->count();
                        }
                        break;
                    case 3 :
                        for ( $i=1;$i<13;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }
                            $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                            $begin1=$data['month'].$i1.'01 23:59:59';
                            $last=strtotime($begin1." +1 month -1 day");
                            $key[]=$i1.'月';
                            $quxiao[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>0])->count();
                            $daifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>1])->count();
                            $yifu[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>2])->count();
                            $yifa[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>3])->count();
                            $yishou[]=$model->where(['uptime'=>['between',[$begin,$last]],'state'=>4])->count();

                        }
                        break;
                }
            }
            return ['key'=>$key,'quxiao'=>$quxiao,'daifu'=>$daifu,'yifu'=>$yifu,'yifa'=>$yifa,'yishou'=>$yishou];
        }

    }
    //提现统计
    public function tixian(){
        if($this->request->isAjax()){
            $model=new MoneyLog();
//            $ordermodel=new Order();
            $ordermodel=new ordersModel();
            $data=$this->request->post();
            $where=$where1=$key=$tixian=$fanyong =$order=$where2=$where3=$list=$xiaofei=[];
            $where['type']=1;
            $where['type2']=1;
            $where1['type']=0;
            $where1['type2']=0;
            $where2['state']=['gt',1];
            $where3['type2']=2;
            if($data['type']==3 && $data['month']==1){
                $data['month']=date('Y');
                for ( $i=1;$i<13;$i++){
                    $i1=$i;
                    if(strlen($i)==1){
                        $i1='0'.$i;
                    }

                    $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                    $begin1=$data['month'].$i1.'01 23:59:59';
                    $last=strtotime($begin1." +1 month -1 day");

                    $where['addtime']=['between',[$begin,$last]];
                    $where1['addtime']=['between',[$begin,$last]];
                    $where2['uptime']=['between',[$begin,$last]];
                    $where3['addtime']=['between',[$begin,$last]];
                    $key[]=$i1.'月';
                    $tixian[]=$model->where($where)->sum('agent_price');
                    $fanyong[]=$model->where($where1)->sum('agent_price');
                    $xiaofei[]=$model->where($where3)->sum('agent_price');
                    $order[]=$ordermodel->where($where2)->sum('total_money');

                }
            }else{
                switch ($data['type']){
                    case 1 :
                        for ( $i=0;$i<24;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].' '.$i1.':00:00');
                            $last=strtotime( $data['month'].' '.$i1.':59:59');

                            $where['addtime']=['between',[$begin,$last]];
                            $where1['addtime']=['between',[$begin,$last]];
                            $where2['uptime']=['between',[$begin,$last]];
                            $where3['addtime']=['between',[$begin,$last]];
                            $key[]=$i1.'时';
                            $tixian[]=$model->where($where)->sum('agent_price');
                            $fanyong[]=$model->where($where1)->sum('agent_price');
                            $xiaofei[]=$model->where($where3)->sum('agent_price');
                            $order[]=$ordermodel->where($where2)->sum('total_money');

                        }
                        break;
                    case 2 :

                        $len=date('t', strtotime($data['month'].'-01 00:00:00'));
                        for ( $i=1;$i<=$len;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].'-'.$i1.' 00:00:00');
                            $last=strtotime( $data['month'].'-'.$i1.' 23:59:59');
                            $where['addtime']=['between',[$begin,$last]];
                            $where1['addtime']=['between',[$begin,$last]];
                            $where2['uptime']=['between',[$begin,$last]];
                            $where3['addtime']=['between',[$begin,$last]];
                            $key[]=$i1;
                            $tixian[]=$model->where($where)->sum('agent_price');
                            $fanyong[]=$model->where($where1)->sum('agent_price');
                            $xiaofei[]=$model->where($where3)->sum('agent_price');
                            $order[]=$ordermodel->where($where2)->sum('total_money');
                        }
                        break;
                    case 3 :
                        for ( $i=1;$i<13;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                            $begin1=$data['month'].$i1.'01 23:59:59';
                            $last=strtotime($begin1." +1 month -1 day");

                            $where['addtime']=['between',[$begin,$last]];
                            $where1['addtime']=['between',[$begin,$last]];
                            $where2['uptime']=['between',[$begin,$last]];
                            $where3['addtime']=['between',[$begin,$last]];
                            $key[]=$i1.'月';
                            $tixian[]=$model->where($where)->sum('agent_price');
                            $fanyong[]=$model->where($where1)->sum('agent_price');
                            $xiaofei[]=$model->where($where3)->sum('agent_price');
                            $order[]=$ordermodel->where($where2)->sum('total_money');

                        }
                        break;
                }
            }
            return ['key'=>$key,'tixian'=>$tixian,'fanyong'=>$fanyong,'order'=>$order,'xiaofei'=>$xiaofei];
        }

    }

    //回购统计
    public function huigou(){
        if($this->request->isAjax()){
            $data=$this->request->post();
            $where=$where1=$key=$tixian=$fanyong =$list=$fail=[];
            $where['payid']=100;
            $where1['payid']=100;
            $where['status']=2;
            $where1['status']=5;
            $where2['status']=0;
            $where2['payid']=100;
            if($data['type']==3 && $data['month']==1){
                $data['month']=date('Y');
                for ( $i=1;$i<13;$i++){
                    $i1=$i;
                    if(strlen($i)==1){
                        $i1='0'.$i;
                    }

                    $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                    $begin1=$data['month'].$i1.'01 23:59:59';
                    $last=strtotime($begin1." +1 month -1 day");

                    $where['update_time']=['between',[$begin,$last]];
                    $where1['update_time']=['between',[$begin,$last]];
                    $where2['update_time']=['between',[$begin,$last]];
                    $key[]=$i1.'月';
                    $tixian[]=Db::name('tpc2c')->where($where)->sum('total_money');
                    $fanyong[]=Db::name('tpc2c')->where($where1)->sum('total_money');
                    $fail[]=Db::name('tpc2c')->where($where2)->sum('total_money');
                }
            }else{
                switch ($data['type']){
                    case 1 :
                        for ( $i=0;$i<24;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].' '.$i1.':00:00');
                            $last=strtotime( $data['month'].' '.$i1.':59:59');
                            $where['update_time']=['between',[$begin,$last]];
                            $where1['update_time']=['between',[$begin,$last]];
                            $where2['update_time']=['between',[$begin,$last]];
                            $key[]=$i1.'时';
                            $tixian[]=Db::name('tpc2c')->where($where)->sum('total_money');
                            $fanyong[]=Db::name('tpc2c')->where($where1)->sum('total_money');
                            $fail[]=Db::name('tpc2c')->where($where2)->sum('total_money');
                        }
                        break;
                    case 2 :

                        $len=date('t', strtotime($data['month'].'-01 00:00:00'));
                        for ( $i=1;$i<=$len;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].'-'.$i1.' 00:00:00');
                            $last=strtotime( $data['month'].'-'.$i1.' 23:59:59');
                            $where['update_time']=['between',[$begin,$last]];
                            $where1['update_time']=['between',[$begin,$last]];
                            $where2['update_time']=['between',[$begin,$last]];
                            $key[]=$i1;
                            $tixian[]=Db::name('tpc2c')->where($where)->sum('total_money');
                            $fanyong[]=Db::name('tpc2c')->where($where1)->sum('total_money');
                            $fail[]=Db::name('tpc2c')->where($where2)->sum('total_money');
                        }
                        break;
                    case 3 :
                        for ( $i=1;$i<13;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                            $begin1=$data['month'].$i1.'01 23:59:59';
                            $last=strtotime($begin1." +1 month -1 day");

                            $where['update_time']=['between',[$begin,$last]];
                            $where1['update_time']=['between',[$begin,$last]];
                            $where2['update_time']=['between',[$begin,$last]];
                            $key[]=$i1.'月';
                            $tixian[]=Db::name('tpc2c')->where($where)->sum('total_money');
                            $fanyong[]=Db::name('tpc2c')->where($where1)->sum('total_money');
                            $fail[]=Db::name('tpc2c')->where($where2)->sum('total_money');

                        }
                        break;
                }
            }
            return ['key'=>$key,'tixian'=>$tixian,'fanyong'=>$fanyong,'shibai'=>$fail];
        }

    }



    //用户统计
    public function yonghu(){
        if($this->request->isAjax()){
            $model=new \app\admin\model\User();
            $data=$this->request->post();
            $where=$where1=$key=$tixian=$fanyong =$list=[];
            if($data['type']==3 && $data['month']==1){
                $data['month']=date('Y');
                for ( $i=1;$i<13;$i++){
                    $i1=$i;
                    if(strlen($i)==1){
                        $i1='0'.$i;
                    }

                    $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                    $begin1=$data['month'].$i1.'01 23:59:59';
                    $last=strtotime($begin1." +1 month -1 day");

                    $where['addtime']=['between',[$begin,$last]];
                    $where1['last_login_time']=['between',[$begin,$last]];
                    $key[]=$i1.'月';
                    $tixian[]=$model->where($where)->count();
                    $fanyong[]=$model->where($where1)->count();

                }
            }else{
                switch ($data['type']){
                    case 1 :
                        for ( $i=0;$i<24;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].' '.$i1.':00:00');
                            $last=strtotime( $data['month'].' '.$i1.':59:59');

                            $where['addtime']=['between',[$begin,$last]];
                            $where1['last_login_time']=['between',[$begin,$last]];
                            $key[]=$i1.'时';
                            $tixian[]=$model->where($where)->count();
                            $fanyong[]=$model->where($where1)->count();

                        }
                        break;
                    case 2 :

                        $len=date('t', strtotime($data['month'].'-01 00:00:00'));
                        for ( $i=1;$i<=$len;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].'-'.$i1.' 00:00:00');
                            $last=strtotime( $data['month'].'-'.$i1.' 23:59:59');
                            $where['addtime']=['between',[$begin,$last]];
                            $where1['last_login_time']=['between',[$begin,$last]];
                            $key[]=$i1;
                            $tixian[]=$model->where($where)->count();
                            $fanyong[]=$model->where($where1)->count();
                        }
                        break;
                    case 3 :
                        for ( $i=1;$i<13;$i++){
                            $i1=$i;
                            if(strlen($i)==1){
                                $i1='0'.$i;
                            }

                            $begin=strtotime( $data['month'].$i1.'01 00:00:00');
                            $begin1=$data['month'].$i1.'01 23:59:59';
                            $last=strtotime($begin1." +1 month -1 day");

                            $where['addtime']=['between',[$begin,$last]];
                            $where1['last_login_time']=['between',[$begin,$last]];
                            $key[]=$i1.'月';
                            $tixian[]=$model->where($where)->count();
                            $fanyong[]=$model->where($where1)->count();

                        }
                        break;
                }
            }
            return ['key'=>$key,'tixian'=>$tixian,'fanyong'=>$fanyong];
        }

    }


}
