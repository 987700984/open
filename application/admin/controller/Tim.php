<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/30
 * Time: 15:52
 */

namespace app\admin\controller;


use app\admin\extend\alipay\AlipayFundTransToaccountTransferRequest;
use app\admin\extend\alipay\AopClient;
use app\admin\model\CoinUserAdmin;
use app\admin\model\Tpbill;
use app\admin\model\Tpintegral;
use think\Controller;


class Tim extends Controller
{

    /*定时返还糖果给用户*/
    public function tim()
    {
        $model = new CoinUserAdmin();
        $where = [];
        $where['status'] = 2;
        $where['is_instant'] = 2;
        $where['is_delete'] = 0;
        $where['no_amount'] = ['gt', 0];
        $where['addtime'] = ['lt', strtotime(date('Y-m-d 00:00:00'))];
        $list = $model->where($where)->select();

        if ($list) {
            $msg = '每天定时发放糖果';
            $integral = new Tpintegral();
            foreach ($list as $k => $v) {
                if ($v['is_employees'] == 1) {
                    $amount = $v['no_amount'] / ($v['timing'] - $v['cash_days']);
                } else {
                    $amount = $v['amount'] / $v['timing'];
                }
                if ($v->timing == $v->cash_days + 1) {
                    $v->status = 1;
                    $v->cash_amount = $v['amount'];
                    $v->no_amount = 0;

                } else {
                    $v->cash_amount += $amount;
                    $v->no_amount -= $amount;
                }
                $v->cash_days += 1;
                $v->addtime = time();
                $status = $v->save();
                $param = [
                    'amount' => $amount,
                    'sid' => $v['sid']

                ];
                if ($status) {
                    $integral->charge($param, $v['uid'],$v['id'], $msg);
                }

            }
        }
    }


    public function index(){
        $model=new Tpbill();
        $list=$model->where(['type'=>0,'type2'=>7,'coin_id'=>0,'content'=>'每天定时发放糖果'])->select();
        $model1=new CoinUserAdmin();
        $a=0;
        foreach ($list as $ke=>$v){
            $info=$model1->where(['uid'=>$v['uid'],'sid'=>$v['sid'],'is_instant'=>2,'status'=>2])->find();
            if($info){
                $v->coin_id=$info['id'];
                $v->save();
                $a++;
            }
        }
        echo $a;
    }


}