<?php
namespace app\admin\controller;

use think\Db;

class C2cbuy extends Base
{

    public function index(){
        if(request()->isAjax()){
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $selectResult = Db::name('tpc2c')->where(['payid'=>100])->order('status,id desc')->limit($offset, $limit)->select();
            foreach ($selectResult as $key => $vo){
                $selectResult[$key]['create_time'] = date('Y-m-d H:i:s', $vo['create_time']);
                $selectResult[$key]['update_time'] = date('Y-m-d H:i:s', $vo['update_time']);
                $selectResult[$key]['sid'] =Db::name('tpsoretype')->where(['id'=>$vo['sid']])->value('name');
                $arr=['失败','待接单','待处理','已支付','已发币','已完成'];
                $selectResult[$key]['status'] =$arr[$vo['status']];
                $selectResult[$key]['type'] = $vo['type'] == 1 ? '出售' : '求购';


                if($vo['status']==2){
                    $operate = [
                        '详情' => url('c2cbuy/det', ['id' => $vo['id']]),
                        '通过' => "javascript:pass(".$vo['id'].")",
                        '不通过' => "javascript:notpass(".$vo['id'].")",
                    ];
                    $selectResult[$key]['operate'] = showOperate($operate);
                }

            }

            $return['total'] = Db::name('tpc2c')->where(['payid'=>100])->count();  //总数据
            $return['rows'] = $selectResult;
            return json($return);
        }

        $config = Db::name('config')->where(['id'=>2])->value('content');
        $this->assign('config',json_decode($config,true));
        $this->assign([
            'allcount'=>Db::name('tpc2c')->where(['payid'=>100,'status'=>5])->sum('num'),
            'allmoney'=>Db::name('tpc2c')->where(['payid'=>100,'status'=>5])->sum('total_money')
        ]);
        return $this->fetch();
    }

    public function det(){
        $id = input('id/d');
        $res = Db::name('tpc2c')->where(['id'=>$id,'status'=>2])->find();
         $res['s_name'] = Db::name('tpsoretype')->where(['id'=>$res['sid']])->value('name');
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function edit(){
        $id = input('id/d');
        $status = input('status/d');

        $value = Db::name('tpc2c')->where(['id'=>$id,'status'=>2])->find();
        if(empty($value)){
            return json(['code'=>0,'msg'=>'订单不存在']);
        }

        if($status == 1){
            //判断是否支持支付宝
            if(empty($value['zhifu_id']) || empty($value['zhifu_name'])){
                return json(['code'=>0,'msg'=>'不支持支付宝，请人工转账']);
            }

            //支付宝打款
            $moneyinfo['oid'] = $value['cid'];
            $moneyinfo['money'] = $value['total_money'];
            $moneyinfo['zhifu_id'] = $value['zhifu_id'];
            $moneyinfo['zhifu_name'] = $value['zhifu_name'];
            $moneyinfo['msg'] = 'wetoken购买订单'.$value['cid'];

            $res = $this->alipay($moneyinfo);

            if($res['status'] != 1) {
                return json(['code' => 0, 'msg' => $res['msg']]);
            }else{
                //设置成已付款
                Db::name('tpc2c')->where(['id'=>$value['id']])->update(['status'=>3]);
                $this->log->addLog($this->logData,'审核了自动回购');
                return json(['code'=>1,'msg'=>'回购成功']);
            }
        }elseif($status == 2){
            //人工打款
            //设置成已付款
            Db::name('tpc2c')->where(['id'=>$value['id']])->update(['status'=>3]);
            $this->log->addLog($this->logData,'审核了手动回购');
            return json(['code'=>1,'msg'=>'回购成功']);
        }else{
            //取消订单
            Db::name('tpc2c')->where(['id'=>$id])->update(['status'=>0]);
            //积分解冻
            Db::name('tpintegral')->where(['uid'=>$value['uid'],'sid'=>$value['sid']])->update(['frozen'=>['exp','frozen-'.($value['num']+$value['fee'])],'integral'=>['exp','integral+'.($value['num']+$value['fee'])]]);

            Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'订单已到期']);
            $this->log->addLog($this->logData,'审核取消了回购');
            return json(['code'=>1,'msg'=>'取消成功']);

        }
    }

    public function config(){
        $status = input('status');
        $data = json_encode(['status'=>$status]);
        Db::name('config')->where(['id'=>2])->update(['content'=>$data]);
        if($status == 1){
            $this->log->addLog($this->logData,'开启了回购功能');
            return json(['code'=>1,'msg'=>'C2C回购开启']);
        }else{
            $this->log->addLog($this->logData,'关闭了回购功能');
            return json(['code'=>1,'msg'=>'C2C回购关闭']);
        }
    }
}