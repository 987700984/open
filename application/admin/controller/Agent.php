<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 14:59
 */

namespace app\admin\controller;


use app\admin\model\MoneyLog;
use app\admin\model\UserAgent;
use app\admin\model\UserModel;
use app\admin\model\Withdrawal;
use app\admin\extend\alipay\AopClient;
use app\admin\extend\alipay\AlipayFundTransToaccountTransferRequest;
use think\Db;


class Agent extends Base
{
    //代理列表
    public function index()
    {
        $where2 =$where3= [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where2['sid'] = ['in', $str];
            $where3['id'] = ['in', $str];
        }
        $user = new UserAgent();
        $tpsoretype = Db::name('tpsoretype')->where($where3)->field('id,name')->select();
        $levellist=$user->leve()->where($where2)->select();
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = $where1 = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where1['w.username'] = ['like', '%' . $param['searchText'] . '%'];
                $where1['w.phone'] = ['like', '%' . $param['searchText'] . '%'];
                $where1['w.id'] = ['eq', $param['searchText']];
            }
            if (isset($param['phone']) && !empty($param['phone'])) {
                $where['w.phone'] = ['eq', $param['phone']];
            }
            if (isset($param['level']) && !empty($param['level'])) {
                $where['a.level'] = ['eq', $param['level']];
            }
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                } else {
                    $where['a.sid']= ['in', $str];
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                }
            }
            if ($where1) {
                $selectResult = $user->alias('a')->field('a.*,w.username as name,w.phone')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->where(function ($q) use ($where1) {
                    $q->whereOr($where1);
                })->limit($offset, $limit)->order('a.id desc')->select();
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->where(function ($q) use ($where1) {
                    $q->whereOr($where1);
                })->count();  //总数据
            } else {
                $selectResult = $user->alias('a')->field('a.*,w.username as name,w.phone')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.id desc')->select();
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();  //总数据
            }
            foreach ($selectResult as $key => $vo) {

                $selectResult[$key]['operate'] = '';
                $selectResult[$key]['levelname'] = $vo->leve->name;
                $selectResult[$key]['add_time'] = date('Y-m-d H:i:s', $vo['add_time']);
                $operate = [
                    '编辑' => url('agent/edit', ['id' => $vo['id']]),

                ];
                if($vo->leve->level>2){
                    $operate ['详情']  = url('agent/detail', ['id' => $vo['id']]);
                }
                $selectResult[$key]['operate'] = showOperate($operate);

            }


            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('level', $levellist);
        $this->assign('coin', $tpsoretype);
        return $this->fetch();
    }

    public function detail()
    {
        $user = new UserAgent();
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where  = ['a.agentid'=>$param['sid']];
            $selectResult = $user->alias('a')->field('a.*,w.username as name,w.phone')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.id desc')->select();
            $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();  //总数据
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['levelname'] = $vo->leve->name;
                $selectResult[$key]['add_time'] = date('Y-m-d H:i:s', $vo['add_time']);
            }
            $return['rows'] = $selectResult;
            return json($return);
        }
        $this->assign('id', $this->request->param('id'));
        return $this->fetch();
    }

    //添加总代理
    public function add()
    {
        $where2 = [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where2['sid'] = ['in', $str];
        }
        $role = new UserAgent();
        $tpsoretype = $role->leve()->where($where2)->select();
        if (request()->isPost()) {

            $param = input('param.');
            $param = parseParams($param['data']);
            if (empty($param['phone'])) {
                return json(['code' => -1, 'msg' => '手机号或者不能为空']);
            }
            if (strlen($param['phone']) == 11) {
                $user = UserModel::get(['phone' => $param['phone']]);
            } else {
                $user = UserModel::get($param['phone']);
            }
            if (!$user) {
                return json(['code' => -1, 'msg' => '该用户不存在']);
            }
            $sid=$role->leve()->where('id',$param['level'])->find();
            $agent = $role->where(['uid' => $user['id'],'sid'=>$sid['sid']])->find();

            if ($agent) {
                if ($agent->agentid > 0) {
                    return json(['code' => -1, 'msg' => '该用户已经是代理']);
                }
                $level = $agent->leve->level;
                if($level==$sid['level']){
                    return json(['code' => -1, 'msg' => '已经是该等级']);
                }
                $agent->level = $param['level'];
                $agent->save();
                if($level>2){
                    $role->handle_level($sid['level'] - $level,$sid['sid'],$agent['id']);
                }
            } else {
                $data['add_time'] = time();
                $data['level'] = $param['level'];
                $data['uid'] = $user['id'];
                $data['sid'] = $sid['sid'];
                $role->save($data);
            }
            $this->log->addLog($this->logData,'进行了添加总代理操作');
            return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
        }
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    //编辑会员代理
    public function edit()
    {
        $where2 = [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where2['sid'] = ['in', $str];
        }
        $role = new UserAgent();
        $tpsoretype = $role->leve()->where($where2)->select();
        if (request()->isPost()) {

            $param = input('param.');
            $param = parseParams($param['data']);

            $agent = $role->where(['id'=>$param['id']])->find();
            $level=$agent->leve->level;

            $sid=$role->leve()->where('id',$param['level'])->find();
            if($level>=2 && $sid['level']==1 ){
                $agent->agentid=0;
            }
            $agent->level=$param['level'];
            $agent->save();
            if($level>2){
                $role->handle_level($sid['level'] - $level,$sid['sid'],$agent['id']);
            }
            $this->log->addLog($this->logData,'进行了编辑代理操作');
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }
        $id = input('id/d');
        $mode = new UserAgent();
        $user = $mode->where(['id' => $id])->find();
        $this->assign('agent', $user);
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    /*会员代理提现*/

    public function agent_tixian()
    {
        $where1 = [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in', $str];
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where =$where2=  [];
            if (isset($param['phone']) && !empty($param['phone'])) {
                $where2['w.phone'] = ['eq', $param['phone']];
                $where2['w.id'] = ['eq', $param['phone']];
            }
            if (!empty($param['ti_status']) || $param['ti_status']=='0') {
                $where['a.ti_status'] = ['eq', $param['ti_status']];
            }
            if (isset($param['type']) && !empty($param['type'])) {
                $where['a.type'] = ['eq', $param['type']];
            }
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                } else {
                    $where['a.sid']= ['in', $str];
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                }
            }
            $user = new Withdrawal();
            if($where2){
                $selectResult = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->where(function ($q) use ($where2) {
                    $q->whereOr($where2);
                })->limit($offset, $limit)->order('a.id desc')->select();
            }else{
                $selectResult = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.id desc')->select();
            }

            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = '';
                switch ($vo['type']) {
                    case 1:
                        $selectResult[$key]['type'] = '支付宝';
                        break;
                    case 2:
                        $selectResult[$key]['type'] = '银行卡';
                        break;
                    case 3:
                        $selectResult[$key]['type'] = '微信';
                        break;
                }

                $selectResult[$key]['phone'] = $vo->user->phone;
                $selectResult[$key]['ali_phone'] = $vo->ali_phone ? $vo->ali_phone : '- - - -';
                $selectResult[$key]['real_name'] = $vo->rea_name ? $vo->rea_name : '- - - -';
                $selectResult[$key]['bank_type'] = $vo->bank_type ? $vo->bank_type : '- - - -';
                $selectResult[$key]['bank_number'] = $vo->bank_number ? $vo->bank_number : '- - - -';
                $selectResult[$key]['addtime'] = date('Y-m-d H:i:s',$vo['addtime']);
                $selectResult[$key]['amount'] = $vo->amount;
                if ($vo['ti_status'] < 1) {

                    $operate = [
                        '自动通过' => "javascript:roleDel('" . $vo['id'] . "')",
                        '不通过' => "javascript:NoDel('" . $vo['id'] . "')",
                        '人工发放' => "javascript:NDel('" . $vo['id'] . "')",
                        '资金详情' => url('agent/money_detail', ['id' => $vo['uid']]),

                    ];


                } else {
                    $operate= [ '资金详情' => url('agent/money_detail', ['id' => $vo['uid']])];
                }
                $selectResult[$key]['operate'] = showOperate($operate);
                switch ($vo['ti_status']) {
                    case 0:
                        $selectResult[$key]['ti_status'] = '待提现';
                        break;
                    case 1:
                        $selectResult[$key]['ti_status'] = '提现成功';
                        break;
                    case 2:
                        $selectResult[$key]['ti_status'] = '审核不通过';
                        break;
                    case 3:
                        $selectResult[$key]['ti_status'] = '后台手动发放';
                        break;
                }

            }
            if($where2){
                $return['total'] = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->where(function ($q) use ($where2) {
                    $q->whereOr($where2);
                })->count();
            }else{
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();
            }
              //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign('coin', $tpsoretype);
        return $this->fetch();
    }


    /*资金流水*/

    public function money_log()
    {
        $where1 = [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in', $str];
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            $where1 = [];
            $where2 = [];
            if (isset($param['phone']) && !empty($param['phone'])) {
                $where1['w.phone'] = ['like', '%' . $param['phone'] . '%'];
                $where1['w.username'] = ['like', '%' . $param['phone'] . '%'];
                $where1['w.id'] = ['eq', $param['phone']];
                $where2['w.phone'] = ['like', '%' . $param['phone'] . '%'];
                $where2['w.username'] = ['like', '%' . $param['phone'] . '%'];
                $where2['w.id'] = ['eq', $param['phone']];
            }
            if ($param['type'] || $param['type'] == '0') {
                $where['a.type'] = ['eq', $param['type']];
            }
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                } else {
                    $where['a.sid']= ['in', $str];
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid']= ['eq', $param['sid']];
                }
            }
            $user = new MoneyLog();
            $ordermodel=new \app\shop\model\Order();
            if ($where1) {
                $selectResult = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)
                    ->where(function ($q) use ($where1) {
                        $q->whereOr($where1);
                    })->limit($offset, $limit)->order('a.addtime desc')->select();
            } else {
                $selectResult = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.addtime desc')->select();
            }

            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = '';
                if($vo['agentid']){
                    $selectResult[$key]['agentid'] = $vo['agentid'];
                }else{
                    $selectResult[$key]['agentid'] = '----';
                }
                $selectResult[$key]['phone'] = $vo->user->phone;
                $selectResult[$key]['mobile'] = $vo->user()->where('id',$vo['agentid'])->value('phone');
                $selectResult[$key]['username1'] = $vo->user()->where('id',$vo['agentid'])->value('username');
                $selectResult[$key]['username'] = $vo->user->username;
                $selectResult[$key]['price'] = $vo->agent_price;
                $selectResult[$key]['content'] = $vo->content;
                $selectResult[$key]['type'] = $vo->type == 1 ? '减少' : '增加';
                $selectResult[$key]['addime'] = date('Y-m-d H:i:s',$vo['addtime']);
                //                $operate = [
                ////                    '编辑' => url('level/edit', ['id' => $vo['id']]),
                ////                    '删除' => "javascript:roleDel('".$vo['id']."')",
                ////                    '分配权限' => "javascript:giveQx('".$vo['id']."')"
                //                ];
                //                $selectResult[$key]['operate'] = showOperate($operate);

            }
            if ($where1) {
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)
                    ->where(function ($q) use ($where1) {
                        $q->whereOr($where1);
                    })->count();
                $where['a.type']=0;
                $return['fanyong'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)
                    ->where(function ($q) use ($where2) {
                        $q->whereOr($where2);
                    })->sum('agent_price');
                $return['order'] = $ordermodel->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)
                    ->where(function ($q) use ($where2) {
                        $q->whereOr($where2);
                    })->sum('total_money');
                $where['a.type']=1;
                $return['tixian'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)
                    ->where(function ($q) use ($where2) {
                        $q->whereOr($where2);
                    })->sum('agent_price');

            } else {
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();  //总数据
                $return['order'] = $ordermodel->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->sum('total_money');
                $where['a.type']=0;
                $return['fanyong'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->sum('agent_price');
                $where['a.type']=1;
                $return['tixian'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->sum('agent_price');
            }

            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign('coin', $tpsoretype);
        return $this->fetch();
    }


    /*提现详情*/

    public function money_detail($id)
    {


        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (isset($param['uid']) && !empty($param['uid'])) {
                $where['w.id'] = ['eq',$param['uid']];

            }
            $user = new MoneyLog();

                $selectResult = $user->alias('a')->field('a.*')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.addtime desc')->select();
            foreach ($selectResult as $key => $vo) {
//                $selectResult[$key]['operate'] = '';
                if($vo['agentid']){
                    $selectResult[$key]['agentid'] =$vo['agentid'];
                }else{
                    $selectResult[$key]['agentid'] = '----';
                }
                $selectResult[$key]['mobile'] = $vo->user()->where('id',$vo['agentid'])->value('phone');
                $selectResult[$key]['username'] = $vo->user()->where('id',$vo['agentid'])->value('username');
                $selectResult[$key]['price'] = $vo->agent_price;
                $selectResult[$key]['content'] = $vo->content;
                $selectResult[$key]['type'] = $vo->type == 1 ? '减少' : '增加';
                $selectResult[$key]['addime'] = date('Y-m-d H:i:s',$vo['addtime']);

            }

            $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('use',UserAgent::get(['uid'=>$id,'sid'=>7]));
        $this->assign('uid', $id);
        return $this->fetch();
    }

    /*修改提现状态*/
    public function up_status()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $modle = Withdrawal::get($data['id']);
            if (isset($data['content'])) {
                $modle->ti_status = 2;
                $modle->content = $data['content'];
                $stat = $modle->save();
            } else {

                if (!$modle) {
                    return ['status' => 0, 'msg' => '转账失败'];
                }
                if ($modle['ti_status'] == 1 || $modle['ti_status'] == 3) {
                    return ['status' => 0, 'msg' => '已经转账成功'];
                }
                if ($modle['type'] != 1) {
                    return ['status' => 0, 'msg' => '目前只支持支付宝提现'];
                }
                $dat = $this->alipay($modle);
                if($dat['status']==1){
                    $user = UserAgent::get(['uid'=>$modle['uid'],'sid'=>$modle['sid']]);
                    $user->has_money += $modle->amount;
                    $user->ice_money -= $modle->amount;
                    $status =$user->save();
                    if ($status) {
                        $log = new MoneyLog();
                        $log->uid = $modle['uid'];
                        $log->agentid = 0;
                        $log->addtime = time();
                        $log->content = '提现成功';
                        $log->type = 1;
                        $log->type2 = 1;
                        $log->type3 = 3;
                        $log->withdrawal_id =$data['id'];
                        $log->agent_price = $modle->amount;
                        $log->save();

                    }
                }
                $this->log->addLog($this->logData,'进行了提现自动通过操作');
                return $dat;
            }

            if (isset($data['content'])) {
                if ($stat) {
                    $user = UserAgent::get(['uid'=>$modle['uid'],'sid'=>$modle['sid']]);
                    $user->money += $modle->amount;
                    $user->ice_money -= $modle->amount;
                    $user->save();
                    $this->log->addLog($this->logData,'进行了提现审核不通过操作');

                }
            }
            return ['code' => 1];
        }

    }

    /*人工发放*/
    public function person_pay(){
        if (request()->isAjax()) {
            $data = input('post.');
            $modle = Withdrawal::get($data['id']);
            $modle->ti_status = 3;
            $status =$modle->save();
            if($status){
                $user = UserAgent::get(['uid'=>$modle['uid'],'sid'=>$modle['sid']]);
                $user->has_money += $modle->amount;
                $user->ice_money -= $modle->amount;
                $status =$user->save();
                if ($status) {
                    $log = new MoneyLog();
                    $log->uid = $modle['uid'];
                    $log->agentid = 0;
                    $log->addtime = time();
                    $log->content = '提现成功';
                    $log->type = 1;
                    $log->type2 = 1;
                    $log->type3 = 3;
                    $log->withdrawal_id =$data['id'];
                    $log->agent_price = $modle->amount;
                    $log->save();

                }
                $this->log->addLog($this->logData,'进行了提现人工发放操作');
            }

            return ['status' => 1, 'msg' => '转账成功'];
        }
    }

    /*支付宝转账*/

    protected function alipay($moneyinfo)
    {



        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2018051760142845';
        $aop->rsaPrivateKey = 'MIIEogIBAAKCAQEAw9cQNrMGD8/O31+d5t7YX8HukfKD2j1snjFj5o09OXPU8sbOybivgcujb2guOpbrujH0726LY43Oz3FLpTKoHMg0tnz+EvTb69dXd5Lxri6lpnVlpGATYdyqsH0VocprOVB3F35Uj4UgsGzDHnXEHw0o9iNP4p/cwA4OF+rkerD4VGzed5nfqx9JVzpa1l5G5FxKrhUz5uCalYEDHpZa2DyszwCshigWZbP+QOtE+MPNFfJhI1/Hopj2ciPggV7tQ1K3Z4ufg/4BN0BOCyuCbrYXB8RjiB3CijEJnhaoXERdN/hEl9OCveqTnEsqZ+xLOQQENNyr6h2RuLQXXsr7lQIDAQABAoIBAFCbkdnh4AncEBtTAOxJJyhq62Z5Opo2lCGc1LDNy7h9G9Z9zBmtgdfb2L5/VB/bhNVTwKxYhNkKQmiSCn/JlPab1U6TrgRhcq/lJ+RYwE9gdeBJC/gXb4LlUABqy9+XMIEbxJkP74BPXIAhlEJSWNIrGYQOTtBJ2pPWdSiVD0wMGML1Y1rNdFYU50xRRqmR82kqx9ba38XNx9vNp6h6gVsJDmjoApDBMqCmjF3Sodub4vX2PFlvgUzJaAJoMhkPdYV95d5cGLsvdNL31cCoeUmaXkyrEV7TUJ/p9E6wYZ+z+SCATOZJXvsQzSg7pGY+OqndyhXhsQ5yrnH8H76fQ+ECgYEA7wZ17BP7R3+oNF9G2vXUmMxANZCL5YwNhkSOeze3rtyy4GvVVMPnVeYlDjbGtXFPDEOoOlvyFSS5w/LKK9zwrP0mRhfryIZ/PDpyFDPoa8ussqe14wNVIptweqI9sXPGW1TXqERHZq5IxwC+AfHrZxtnWq+tfntBLyPAJybKkn0CgYEA0b98Xhem1xNgZ/MKDHBd02Fc/vCo2vJZs7f8483oqqiF4lkkwvaOWanE9zEXRJAuF76Hb8pwNIZK4qEO0pdWmvQ/51uoiqD7dkfbnCB8oOGBQU7uxpOtSdqNKDn+JPaxiBqEnYQ9Qw1sQMcVzWwBF1y6OY6jgoCjfe+bpyfUgPkCgYAbIpCgjGQqacOernJMyTupXQatDgvTs2KVq5LBSkIAB+4GrDc7uEG67rWmN3G3h3WB3uxqM6X34IN2S0nIUPzBpruBmZWa3inznG72/C2Wjzi7z25Gp0oy85KBWYnHa21JUQhqgdXZQk/gx6TKc7xVqDbDhM4dXcC4qUZXK4AzwQKBgAuwI/oKT13E0qZ4QKMYz+Grl0cNmhs5Tg5Zvlnja4e3BF7soMPMgXo1n6g2sBk9/5OLJnjwSvhiU2H1n6HUlRrlaXo2/VlcRyb8MhytIsTETDObdmrSZ+GpsBwBv0vIA3SWJbWxXMiTwuiJL4nW7uiiXi4+6JWpHXMzGvVhntHhAoGAZBjDoDTUBQKDJoxLcbyZhtMsf+2E3vesk3LXTTyM7MBH5lcq1YXARIUzai4j7okItXJQ0ryr3Sq22ExPJz+Nw2WFYbkGa9/foRKEuh46cBDCj03NnsMIW16972VTwHu1Lebvk4CFd/jSb+njBgTuJZ+NQby2sunZIJKnpAOfi7s=';
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqO4K0oAmrdTehM7IjSS5GZMSLJgLvPLgWGeHmRY3BCjSr+n45Xbg4FmVsoZkhSPz4ayanxeIdi1s26+bmk5nKoOolKyiEquLk0vyAEapfsOhMy/3gbbQ2piI2l8girZaVXsqTtLO/gkLpYUy6C24FDaoIgNZyMeOk46oGS3GdSsauKvSaJWAIsOpD4Dk1gIvTPQcY3/OHtQ+zUKKQ++aDDZGvQa5HSAPNB2rG3yGiaBYKydkl8bvHPk1setBx+2MaAqwZFkVaXFnzv8bEbFhtjGFBTZ+5xgo3PwiYwnCskGRg/asNj8XS3KIyRrKnDZ/On/IEYTh0uELXKRGbrUyGwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';
        $request = new AlipayFundTransToaccountTransferRequest ();
//        $userinfo = UserModel::get(['id' => $moneyinfo['uid']]);
//        $payinfo = PayInfo::get(['uid' => $moneyinfo['uid'], 'id' => $userinfo['pay_info']]);
        $data = [
            'out_biz_no' => time() . rand(1000, 9999) . $moneyinfo['uid'],
            'payee_type' => 'ALIPAY_LOGONID',
            'payee_account' => $moneyinfo['ali_phone'],
            'payee_real_name'=>$moneyinfo['rea_name'],
            'amount' => sprintf("%.2f",($moneyinfo['amount']-$moneyinfo['poundage'])).'',
            'remark' => '提现',
//            'amount' => '0.10',
        ];
        $data = json_encode($data);
        $request->setBizContent($data);
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            $moneyinfo->ti_status = 1;
//            $moneyinfo->ali_phone = $payinfo['zhifu_id'];
//            $moneyinfo->rea_name = $payinfo['zhifu_name'];
            $moneyinfo->save();
            return ['status' => 1, 'msg' => '转账成功'];
        } else {
            return ['status' => 0, 'msg' => '转账失败,' . $result->$responseNode->sub_msg];
        }
    }

    //资金流水导出
    public function out(){



        if(request()->post()){
            $param = input('param.');
            $where = '1=1';

            if(isset($param['phone']) && !empty($param['phone'])){
                $where .= ' and (u.phone = '.$param['phone'].' or u.id='.$param['phone'].')';
            }
            if(($param['starttime']) && !empty($param['starttime'])){
                $starttime = strtotime($param['starttime']);
                $where .= ' and a.addtime > '.$starttime;
            }
            if(isset($param['endtime']) && !empty($param['endtime'])){
                $endtime = strtotime($param['endtime']);
                $where .= ' and a.addtime <'.$endtime.' ';
            }

            $bill = new MoneyLog();
            $selectResult =$bill->alias('a')->field('a.id,u.username,u.phone,content,a.type,agent_price,a.addtime,agentid')->join('__TPUSER__ u', 'a.uid = u.id')->where($where)->limit(5000)->select();


            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]=$vo->toArray();
                    $selectResult[$key]['type'] = $vo->type == 1 ? '减少' : '增加';
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s',$vo['addtime']);
                    $selectResult[$key]['mobile'] = $vo->user()->where('id',$vo['agentid'])->value('phone');
                    $selectResult[$key]['username1'] = $vo->user()->where('id',$vo['agentid'])->value('username');
                }

                $fileheader = array('流水ID','会员名','会员手机','资金类型','状态','金额','时间','下级UID','下级手机号','下级姓名');
                $this->exportExcel($selectResult,'资金流水表',$fileheader);
                exit();
            }

            $this->error('没有数据','bill/out');
        }


        return $this->fetch();
    }

    //个人资金流水导出
    public function user_out(){




            $param = input('param.');
            $where = [];

            if(isset($param['id']) && !empty($param['id'])){
                $where ['a.uid']=$param['id'];
            }else{
                return '';
            }
            $bill = new MoneyLog();
            $selectResult =$bill->alias('a')->field('a.id,u.username,u.phone,content,a.type,agent_price,a.addtime,agentid')->join('__TPUSER__ u', 'a.uid = u.id')->where($where)->limit(5000)->select();


            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]=$vo->toArray();
                    $selectResult[$key]['type'] = $vo->type == 1 ? '减少' : '增加';
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s',$vo['addtime']);
                    $selectResult[$key]['mobile'] = $vo->user()->where('id',$vo['agentid'])->value('phone');
                    $selectResult[$key]['username1'] = $vo->user()->where('id',$vo['agentid'])->value('username');
                }

                $fileheader = array('流水ID','会员名','会员手机','资金类型','状态','金额','时间','下级UID','下级手机号','下级姓名');
                $this->exportExcel($selectResult,'个人资金流水表',$fileheader);
                exit();
            }

            $this->error('没有数据','bill/out');




    }

}