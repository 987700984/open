<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 19:21
 */

namespace app\api\controller;

use app\admin\model\PayInfo;
use app\api\model\CoinUserAdmin;
use app\api\model\AgentLevel;
use app\api\model\MoneyLog;
use app\api\model\Tpbill;
use app\api\model\Tpintegral;
use app\api\model\UserAgent;
use app\api\model\UserModel;
use app\api\model\Withdrawal;
use think\Db;

Class Agent extends Common
{
    protected function _initialize() {
        parent::_initialize();

        $this->param=get_input_data();
        if(!isset($this->param['sid']) || empty($this->param['sid'])){
            $this->param['sid']=7;
//            return json(['status' => 0, 'msg' => 'sid参数错误']);
        }

    }
    //我的代理api
    public function myAgent()
    {
        $user = session('user');
        $agent = UserAgent::get(['uid' => $user['id']]);
        if (!$agent) {
            $level = 1;
            $sid = get_input_data('sid');
        } else {
            $sid = $agent->leve->sid;
            $level = $agent->leve->level - 1;
        }

        $level = AgentLevel::field('id,name')->where(['level' => ['between', [2, $level]], 'sid' => $sid])->limit(3)->order('level desc')->select();
        if ($level) {
            foreach ($level as $k => $v) {
                $level[$k]['count'] =$v['id'];
                $level[$k]['count'] = UserAgent::where(['agentid' => $agent['id'], 'level' => $v['id']])->count();
                $level[$k]['max_num'] = $agent->leve->max_num;
            }
            return json(['status' => 1, 'msg' => '获取数据成功', 'data' => $level]);
        } else {
            return json(['status' => 0, 'msg' => '您还没有代理']);
        }

    }

    /*获取糖果总数*/

    public function get_coin_list()
    {


        $user = session('user');
        $model = new CoinUserAdmin();
        $remain_total = $model->field('id,timing,amount,typeid,cash_amount,no_amount,buytime,cash_days')->where(['uid' => $user['id'], 'sid' => $this->param['sid'], 'is_delete' => 0])->select();
        $arr = [];

        if ($remain_total) {
            foreach ($remain_total as $k => $v) {
                $arr[$k]['cash_days'] = $v['cash_days'];
                $arr[$k]['amount'] = $v['amount'];
                $arr[$k]['cash_amount'] = $v['cash_amount'];
                $arr[$k]['no_amount'] = $v['no_amount'];
                $arr[$k]['id'] = $v['id'];
                $arr[$k]['title'] = $v->typ->name;
                $arr[$k]['content'] = $v->typ->content;
                $arr[$k]['buytime'] = date('Y-m-d H:i:s', $v['buytime']);
                $arr[$k]['nodays'] = $v['timing'] - $v['cash_days'];
            }
        }

        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => $arr]);
    }

    /*获取糖果发放记录*/
    public function get_log()
    {

        $user = session('user');
        $model = new Tpbill();
        $list1 = $model->field('id,price,addtime')->where(['uid' => $user['id'] ,'sid' => $this->param['sid'], 'type2' => 7, 'type' => 0])->order('addtime desc')->select();
        foreach ($list1 as $k => $v) {
            $list1[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }
        $where = ['uid' => $user['id'], 'sid' => $this->param['sid']];
        $where['type2'] = ['neq', 7];
        $list2 = $model->field('addtime,id,type,price,type2')->where($where)->order('addtime desc')->select();
        foreach ($list2 as $k => $v) {
            $list2[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }
        return json(['status' => 1, 'msg' => '获取数据成功', 'list1' => $list1, 'list2' => $list2]);

    }

    /*获取糖果发放记录*/
    public function get_new_log()
    {

        $user = session('user');
        $model = new Tpbill();
        $id=get_input_data('id');
        $list1 = $model->field('id,price,addtime')->where(['uid' => $user['id'],'coin_id'=>$id, 'type2' => 7, 'type' => 0,'content'=>'每天定时发放糖果'])->order('addtime desc')->select();
        foreach ($list1 as $k => $v) {
            $list1[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }

        return json(['status' => 1, 'msg' => '获取数据成功', 'list1' => $list1]);

    }


    //等级代理数据

    public function agent_list()
    {

        if (isset($this->param['keyword'])) {
            $where['w.phone'] = ['like', '%' . $this->param['keyword'] . '%'];
        }
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $agentmodel = new UserAgent();
        $user = session('user');
        $agent = $agentmodel->field('id,level')->where(['uid' => $user['id'],'sid'=>$this->param['sid']])->find();
        $where['a.agentid'] = $agent['id'];
        $where['a.level'] = $this->param['level_id'];
        $where['a.sid'] = $this->param['sid'];
        $count = $agentmodel->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();
        $agentlist = $agentmodel->alias('a')->field('a.id,w.username,w.phone,a.uid,a.add_time')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($row)->page($p)->order('a.add_time desc')->select();
        $arr = [];

        foreach ($agentlist as $k => $v) {
            $arr[$k] = $v;
            $arr[$k]['agentcount'] = $v->get_children_count();
            $arr[$k]['friendcount'] = $v->get_friend_count();
            unset($arr[$k]['id']);
            unset($arr[$k]['uid']);
            unset($arr[$k]['user']);
        }


        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => ['list' => $arr, 'total' => $count]]);


    }

    /*****
     * 代理升降级搜索数据
     */

    public function handle_agent_level_info()
    {


        $agentmodel = new UserAgent();
        $user = session('user');
        $agent = $agentmodel->field('level,id')->where(['uid' => $user['id'],'sid'=>$this->param['sid']])->find();
        if ($agent->leve->level <= 2) {
            return json(['status' => 0, 'msg' => '必须是白金会员及以上才能操作']);
        }
        $where['a.agentid'] = $agent['id'];
        $where['a.sid'] = $this->param['sid'];
        $level = $agent->leve->level - 1;
        $ageninfo =$datalevel= [];
        if (isset($this->param['phone'])) {
            $where['w.phone'] = $this->param['phone'];
            $ageninfo = $agentmodel->alias('a')->field('w.username')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->find();

        }
        if($ageninfo){
            $ageninfo=$ageninfo['username'];
            $dat=1;
            $msg='获取数据成功';
        }else{
            $dat=0;
            $msg='未找到该用户';
        }
        $level = AgentLevel::field('id,name')->where(['level' => ['between', [2, $level]], 'sid' => $agent->leve->sid])->limit(3)->order('level desc')->select();
        if ($level) {
            foreach ($level as $k => $v) {
                $datalevel[]=$v;
                $level[$k]['count'] = UserAgent::where(['agentid' => $agent['id'],'level'=>$v['id']])->count();
                $level[$k]['max_num'] = $agent->leve->max_num;

            }
        }
        $lev = AgentLevel::field('id,name')->where(['sid'=>$agent->leve->sid,'level'=>1])->find();
        $lev['name']='移除代理';
        $datalevel[]=$lev;
        return json(['status' => 1, 'msg' => $msg, 'data' => ['info' => $ageninfo,'data'=>$dat,'levellist' => $level,'levelid'=>$datalevel]]);
    }

    /*****
     * 代理升降级
     */
    public function handle_agent_level()
    {

        $agentmodel = new UserAgent();
        $where['w.phone'] = $this->param['phone'];
        $user = session('user');
        $agent = $agentmodel->field('level,id')->where(['uid' => $user['id'],'sid'=>$this->param['sid']])->find();
        $level = AgentLevel::get($this->param['level_id']);
        if (!$level) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        if (!$level['level'] >= $agent->leve->level) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        $where['a.agentid'] = $agent['id'];
        $where['a.sid'] = $this->param['sid'];
        $agentinfo = $agentmodel->alias('a')->field('a.level,a.id')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->find();
        if (!$agentinfo) {
            return json(['status' => 0, 'msg' => '未找到该用户']);
        }
        if ($agentinfo['level'] == $this->param['level_id']) {
            return json(['status' => 0, 'msg' => '已经是此等级']);
        }
        $l = $level['level'] - $agentinfo->leve->level;

        $agentmodel->handle_level($agentinfo['id'], $l, $level['sid']);
        return json(['status' => 1, 'msg' => '操作成功']);

    }

    /*****
     * 授权代理数据
     */
    public function author_agent_level()
    {

        $agentmodel = new UserAgent();
        $user = session('user');
        $agent = $agentmodel->field('level,id')->where(['uid' => $user['id'],'sid'=>$this->param['sid']])->find();
        if ($agent->leve->level <= 2) {
            return json(['status' => 0, 'msg' => '必须是白金会员及以上才能操作']);
        }
        $where['a.agentid'] = $agent['id'];
        $level = $agent->leve->level - 1;
        $ageninfo =$datalevel= [];
        if ( isset($this->param['phone']) && !empty($this->param['phone'])) {
            $ageninfo = UserModel::get(['phone'=>$this->param['phone']]);
        }
        if($ageninfo){
            $ageninfo=$ageninfo['username'];
            $dat=1;
            $msg='获取数据成功';
        }else{
            $dat=0;
            $ageninfo='';
            $msg='未找到该用户';
        }
        $level = AgentLevel::field('id,level,name')->where(['level' => ['between', [2, $level]], 'sid' => $agent->leve->sid])->limit(3)->order('level desc')->select();
        if ($level) {
            foreach ($level as $k => $v) {
                $datalevel[]=$v;
                $level[$k]['count'] = UserAgent::where(['agentid' => $agent['id'],'level'=>$v['id']])->count();
                $level[$k]['max_num'] = $agent->leve->max_num;

            }
        }

        return json(['status' => 1, 'msg' => $msg, 'data' => ['info' => $ageninfo,'data'=>$dat, 'levellist' => $level,'levelid'=>$datalevel]]);

    }

    /*****
     * 授权代理
     */
    public function author_agent()
    {

        $level = AgentLevel::get($this->param['level_id']);
        if (!$level) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        $use = session('user');
        $agentmodel = new UserAgent();
        $agent = $agentmodel->field('level,id')->where(['uid' => $use['id'],'sid'=>$this->param['sid']])->find();
        if (!$level['level'] >= $agent->leve->level) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        $max_num = $agent->leve->max_num;
        $count = $agentmodel->where(['agentid' => $agent['id'], 'level' => $this->param['level_id'],'sid'=>$this->param['sid']])->count();
        if ($max_num == $count) {
            return json(['status' => 0, 'msg' => '该等级代理人数已达上限']);
        }
        $usermodel = new UserModel();
        $user = $usermodel->field('id,rtid')->where(['phone' => $this->param['phone']])->find();


        if (!$user) {
            return json(['status' => 0, 'msg' => '未找到该用户']);
        }
        if($user['rtid']!=$use['phone']){
            $user1=$usermodel->field('id')->where(['phone' => $user['rtid']])->find();
            if($user1){
                $agent1=$agentmodel->where(['uid'=>$user1['id']])->find();
                if($agent1 && $agent1->leve->level>1){
                    return json(['status' => 0, 'msg' => '该用户的上线已经是代理']);
                }
            }
        }
        $agent1 = $agentmodel->where(['uid' => $user['id'],'sid'=>$this->param['sid']])->find();
        if ($agent1) {
            if ($agent1['agentid'] > 0) {
                return json(['status' => 0, 'msg' => '该用户已是代理']);
            } else {
                $agent1->agentid = $agent['id'];
                $agent1->level = $this->param['level_id'];
                $agent1->save();
            }
        } else {
            $agentmodel->agentid = $agent['id'];
            $agentmodel->level = $this->param['level_id'];
            $agentmodel->add_time = time();
            $agentmodel->uid = $user['id'];
            $agentmodel->sid = $level['sid'];
            $agentmodel->save();
        }
        return json(['status' => 1, 'msg' => '操作成功']);

    }

    /*****
     * 假充值api，只限于测试佣金
     */

    public function agent_charge()
    {

        if (isset($this->param['num']) && intval($this->param['num']) > 0) {
            $jifen = new UserModel();
            $use = session('user');
            $agentmodel = new UserAgent();
            $agent = $agentmodel->where(['uid' => $use['id'],'sid'=>$this->param['sid']])->find();
            $jifen->charge($this->param, $agent, $use['id']);
            return json(['status' => 1, 'msg' => '充值成功']);
        } else {
            return json(['status' => 0, 'msg' => '参数错误']);
        }


    }

    /*****
     * 累计佣金页面接口
     */
    public function total_commission()
    {

        $where = [];
        if (isset($this->param['time1']) && isset($this->param['time2'])) {
            $time1 = strtotime($this->param['time1'] . ' 00:00:00');
            $time2 = strtotime($this->param['time2'] . ' 23:59:59');

            $where['addtime'] = ['between', [$time1, $time2]];
        }
        $use = session('user');
        $agentmodel = new UserAgent();
        $agent = $agentmodel->field('level,id,uid,commission')->where(['uid' => $use['id'],'sid'=>$this->param['sid']])->find();
        if (!$agent) {
            return json(['status' => 0, 'msg' => '没有权限']);
        }
        if ($agent->leve->level <= 2) {
            return json(['status' => 0, 'msg' => '代理等级过低']);
        }
        $sid = $agent->leve->sid;
        $arr =$ar= [];
        $levelmodel=new AgentLevel();
        $money=new MoneyLog();
        for ($i = 2; $i < $agent->leve->level; $i++) {
            $leve = $agentmodel->leve()->field('name,id')->where(['level' => $i, 'sid' => $sid])->find();
            $levelname=$leve['name'];
            $agentids=Db::name('user_agent')->field('uid')->where(['sid'=>$this->param['sid'],'level'=>$leve['id'],'agentid'=>$agent['id']])->select();
            if($agentids){
                $uids=[];
                foreach ($agentids as $k=>$v){
                    $uids[]=$v['uid'];
                }
                $where['agentid'] = ['in',$uids];
                $where['uid'] = $use['id'];
                $where['sid'] = $sid;
                $where['type3'] = 2;
                $total = $money->where($where)->sum('agent_price');
                $arr['total'] = $total;
            }else{
                $arr['total'] =0;
            }
            $arr['level_id'] = $levelmodel->where(['sid'=>$sid,'level'=>$i])->value('id');
            $arr['levelname'] = $levelname;
            $ar[]=$arr;
        }
        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => $ar, 'alltotal' => $agent['commission']]);

    }

    /*****
     * 等级佣金明细列表接口
     */

    public function commission_list()
    {

        $where = [];
        if (isset($this->param['phone']) && !empty($this->param['phone'])) {
            $where['w.phone'] = ['like','%'.$this->param['phone'].'%'];
        }
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $use = session('user');
        $agentmodel = new UserAgent();
        $agent = $agentmodel->field('level,id,uid')->where(['uid' => $use['id'],'sid'=>$this->param['sid']])->find();
        $where['a.agentid'] = $agent['id'];
        $where['a.sid'] = $this->param['sid'];
        $where['a.level'] = $this->param['level_id'];
        $where['a.commission2'] = ['gt', 0];
        $count = $agentmodel->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();
        $list = $agentmodel->alias('a')->field('a.commission2 as total,w.phone,w.username,a.add_time')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($row)->page($p)->order('a.add_time desc')->select();
        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => ['list' => $list, 'total' => $count]]);
    }

    /*****
     * 个人佣金明细详情接口
     */

    public function comission_detail()
    {

        $where = [];
        $where['w.phone'] = ['eq',$this->param['phone']];

        if (isset($this->param['year'])) {
            $year = $this->param['year'];
        } else {
            $year = date('Y');
        }
        $use = session('user');
        $agentmodel = new UserAgent();
        $agent = $agentmodel->field('level,id,uid')->where(['uid' => $use['id'],'sid'=>$this->param['sid']])->find();
        $where['a.agentid'] = $agent['id'];
        $where['a.sid'] = $this->param['sid'];
        $list = $agentmodel->alias('a')->field('a.uid,w.phone,w.username,a.commission2')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->find();
        $model = new MoneyLog();
        $arr = [];
        $alltotal = 0;
        if ($list) {
            $where = $ar=[];
            $where['agentid'] = $list['uid'];
            $where['type'] = 0;
            $where['type3'] = 2;
            $where['uid'] = $use['id'];
            for ($i = 1; $i < 13; $i++) {
                $total = 0;
                $i < 10 ? $month = '0' . $i : $month = $i;
                $time1 = $year . '-' . $month . '-01 00:00:00';
                $time2 = $year . '-' . $month . '-31 23:59:59';
                $where['addtime'] = ['between', [strtotime($time1), strtotime($time2)]];
                $total += $model->where($where)->sum('agent_price');
                if ($total > 0) {

                    $arr['total'] = $total;
                    $arr['month'] = $i . '月';
                    $ar[]=$arr;
                    $alltotal += $total;
                }
            }

        } else {
            return json(['status' => 0, 'msg' => '查无此人']);
        }
//        dump(['status' => 1, 'msg' => '获取数据成功', 'data'=>['list' => $arr, 'alltotal' => $alltotal,'info'=>$list]]);
        return json(['status' => 1, 'msg' => '获取数据成功', 'data'=>['list' => $ar, 'alltotal' => $alltotal,'info'=>$list]]);
    }

    /*****
     * 代理管理数据接口
     */

    public function myinfo()
    {

        $use = session('user');
        $agent = UserAgent::get(['uid' => $use['id'],'sid'=>$this->param['sid']]);
        $sid = get_input_data('sid', 7);
        if (!$agent) {
            $levelname = $level = AgentLevel::where(['level' => 1, 'sid' => $sid])->value('name');
            $level = 1;
        } else {
            $levelname = $agent->leve->name;
            $level = $agent->leve->level;
        }

        $agent ? $total = $agent['commission'] : $total = 0;
        return json(['status' => 1, 'msg' => '获取数据成功', 'levelname' => $levelname, 'alltotal' => $total, 'level' => $level]);
    }

    /*获取用户余额和好友分拥*/
    public function get_amount()
    {
        $user = session('user');
        $userinfo = UserModel::get($user['id']);
        $data['username'] = $userinfo['username'];
        $data['phone'] = $userinfo['phone'];
        $data['pay_info'] = $userinfo['pay_info'];
        $sid = get_input_data('sid', 7);
        $dat = Db::name('tpintegral')->field('integral,all_bonus,usable')->where(['uid' => $user['id'], 'sid' => $sid])->find();

        if (!$dat) {

            $dat['integral'] = 0;
            $dat['usable'] = 0;
            $dat['all_bonus'] = 0;


        }
        $data['id'] = $user['id'];
        $data['candyinfo'] = $dat;
        $info = db::name('miner')->find();
        $kjsl = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl');
        $arr = array(
            'force' => $info['initial'] + $kjsl,
            'degree' => $info['degree']
        );
        $agent=UserAgent::get(['uid'=>$user['id'],'sid'=>$this->param['sid']]);
        if($agent){
            $level_name=$agent->leve->name;
        }else{
            $level_name=AgentLevel::where(['level'=>1,'sid'=>$this->param['sid']])->value('name');
        }
        $level = Db::name('tpsoreLevel')->field('name,min,interest')->where(['sid' => $sid])->select();
        $data['levelname'] = $level_name;
        $data['getUserLevel'] = $level;
        $data['calculation_force'] = $arr;
        $data['getCard'] = Db::name('tpuserInfo')->field('content,status,uid')->where(['uid' => $user['id']])->find();
        if (!$data['getCard']) {
            $data['getCard'] = 0;
        }
        $c = file_get_contents(__DIR__ . '/../../config.json');
        $arr = json_decode($c, true);
        $data['getNoticeTime'] = $arr['notice']['time'];
        $notice = Db::name('notice')->where(['sid' => $sid, 'is_personal' => 0])->order('id desc')->find();
        $uids = json_decode($notice['uids'], true);
        if (is_array($uids) && in_array($user['id'], $uids)) {
            $data['getNotice'] = [];
        } else {
            $data['getNotice'] = $notice;
        }
        $agent = new UserAgent();
        $useragent = $agent->where(['uid'=> $user['id'],'sid'=>$sid])->find();
        if (!$useragent) {
            $useragent['money'] = $useragent['commission1'] = 0;
        }
        $data['money'] =  $useragent['money'];;
        $data['commission'] = $useragent['commission1'];
        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => $data]);

    }

    /*判断是否是代理*/
    public function is_agent()
    {
        $uid = session('user.id');

        $model = new UserAgent();
        $agent = $model->where(['uid' => $uid,'sid'=>$this->param['sid']])->find();
        if (!$agent) {
            return json(['status' => 1, 'msg' => '可以购买']);
        }
        if ($agent['agentid'] > 0) {
            return json(['status' => 0, 'msg' => '已经是代理']);
        }
        $goodsid = get_input_data('goodsid');
        $goods = db('tpgoods')->where('goodsid', $goodsid)->find();

        if ($goods['agent_level'] <= $agent->leve->level) {
            return json(['status' => 0, 'msg' => '您不能购买小于您当前等级的代理']);

        }
        return json(['status' => 1, 'msg' => '可以购买']);

    }

    /*模拟购买代理*/
    public function buy()
    {
        $uid = session('user.id');

        $model = new UserAgent();
        $data = $model->buy_agent(['uid' => $uid, 'sid' => 7, 'level' => $this->param['level']]);
        if ($data) {
            return json($data);
        }
        return json(['status' => 1, 'msg' => '可以购买']);
    }

    /*用户提现接口*/
    public function tixian()
    {

        $user=session('user');
        if (!isset($this->param['type'])) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        if ($this->param['type'] !=1) {
            return json(['status' => 0, 'msg' => '目前只支持支付宝提现']);
        }
        if ($this->param['type'] > 3 || $this->param['type'] < 1) {
            return json(['status' => 0, 'msg' => '参数错误']);
        }
        $poundage=Db::name('tpsoretype')->where(['id'=>$this->param['sid']])->value('min_poundage');
        if (intval($this->param['amount']) < $poundage) {
            return json(['status' => 0, 'msg' => '提现金额不得小于'.$poundage]);
        }
        $uid = $user['id'];

        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$uid])->find();
        if($user_info){
            if($user_info['status'] != 2){
                return json(['status'=>0,'msg'=>'实名认证未通过，请耐心等候或联系客服']);
            }
        }else{
            return json(['status'=>0,'msg'=>'请先实名认证']);
        }
        $payinfo=PayInfo::get(session('user.pay_info'));
        if(!$payinfo['zhifu_id']){
            return json(['status' => 0, 'msg' => '请先设置收款支付宝账号']);
        }
       $agent=UserAgent::get(['uid'=>$uid,'sid'=>$this->param['sid']]);
        if ($agent['money'] < floatval($this->param['amount'])) {
            return json(['status' => 0, 'msg' => '余额不足']);
        }
        $model = new Withdrawal();
        $model->trad_no=time().rand(100000,999999).$uid;
        $model->uid = $uid;
        $model->addtime = time();
        $poundage=Db::name('tpsoretype')->where(['id'=>$this->param['sid']])->value('poundage');
        $model->poundage = floatval($this->param['amount'])*$poundage/100;
        $model->rea_name = $payinfo['zhifu_name'];
        $model->ali_phone = $payinfo['zhifu_id'];
        $model->amount = floatval($this->param['amount']);
        $status = $model->save();

        if ($status) {
            $agent->money -= $this->param['amount'];
            $agent->ice_money+=$this->param['amount'];
            $agent->save();
            session('user', null);
        }
        return json(['status' => 1, 'msg' => '提现成功,客服将会在2-3个工作日打到您的账户上']);
    }


    /*好友分拥记录*/

    public function fir_commission()
    {
        $phone = get_input_data('phone');
        if (isset($phone) && $phone) {
            $where['w.phone'] = ['like', "%" . $phone . "%"];
        }
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $user = session('user');
        $model = new MoneyLog();
        $where['a.uid'] = $user['id'];
        $where['a.type'] = 0;
        $where['a.is_friend'] = 1;
        $count = $model->alias('a')->join('__TPUSER__ w', 'a.agentid = w.id')->where($where)->count();
        $list = $model->alias('a')->field('a.id,w.phone,a.content,a.agent_price,a.addtime')->join('__TPUSER__ w', 'a.agentid = w.id')->where($where)->limit($row)->page($p)->order('a.addtime desc')->select();
        $arr = [];
        foreach ($list as $k => $v) {
            $arr[$k]['phone'] = $v['phone'];
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['price'] = $v['agent_price'];
            $arr[$k]['addtime'] = $v['addtime'];
            $arr[$k]['content'] = substr($v['content'], 0, 12);
        }
        $agent = UserAgent::get(['uid' => $user['id']]);
        $level = $agent ? AgentLevel::get([$agent['level']]) : AgentLevel::get(['level' => 1, 'sid' => 7]);
        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => ['list' => $arr, 'total' => $count], 'level' => ['name' => $level['name'], 'comission' => $level['commission1'], 'comission1' => $level['commission2']]]);
    }

    /*我的余额*/
    public function my_amount()
    {
        $user = session('user');
        $data['username'] = $user['username'];
        $use = UserAgent::get(['uid'=>$user['id'],'sid'=>$this->param['sid']]);
        if(!$use){
            $use=[];
            $use['money']=$use['ice_money']=$use['has_money']=0;
        }
        return json(['status' => 1, 'msg' => '获取数据成功', 'data' => ['money' => $use['money'], 'no_amonut' => $use['ice_money'], 'has_amonut' => $use['has_money']]]);
    }

    /*判断是否能提现*/

    public function is_withdrawal(){
        $user = session('user');
        $agent=UserAgent::get(['uid'=>$user['id'],'sid'=>$this->param['sid']]);
        $poundage=Db::name('tpsoretype')->where(['id'=>$this->param['sid']])->value('poundage');
        if($agent['money']<$poundage){
            return json(['status'=>0,'msg'=>'余额不足以提现','data'=>3]);
        }
        //检测是否实名
        $user_info = Db::name('tpuserInfo')->where(['uid'=>$user['id']])->find();
        if($user_info){
            if($user_info['status'] != 2){
                return json(['status'=>0,'msg'=>'实名认证未通过，请耐心等候或联系客服','data'=>3]);
            }
        }else{
            return json(['status'=>0,'msg'=>'请先实名认证','data'=>2]);
        }
        $type=get_input_data('type',1);
        $payinfo=PayInfo::get($user['pay_info']);
        if($type!=1){
            return json(['status' => 0, 'msg' => '目前只支持支付宝提现','data'=>3]);
        }
        if(!$payinfo){
            return json(['status' => 0, 'msg' => '请先设置收款方式','data'=>1]);
        }
        if($type==1){
            if(!$payinfo['zhifu_id']){
                return json(['status' => 0, 'msg' => '请先设置收款支付宝账号','data'=>1]);
            }
        }
        if($type==2){
            if(!$payinfo['yh_id']){
                return json(['status' => 0, 'msg' => '请先设置收款银行卡号','data'=>1]);
            }
        }
         return json(['status' => 1, 'msg' => '可以提现']);
    }

    /*提现记录*/
    public function withdrawal_log(){
        $user = session('user');
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $list=Db::name('withdrawal')->field('type,id,trad_no,ali_phone,rea_name,ti_status,addtime,amount,content,poundage')->where(['uid'=>$user['id'],'sid'=>$this->param['sid']])->limit($row)->page($p)->order('addtime desc')->select();
//        $payinfo=PayInfo::get($user['pay_info']);
        $count=Db::name('withdrawal')->where(['uid'=>$user['id'],'sid'=>$this->param['sid']])->count();
        foreach ($list as $k=>$v){
            switch ($v['type'] ){
                case 1:
                    $list[$k]['type']='支付宝';
                    break;
                case 2:
                    $list[$k]['type']='银行卡';
                    break;
                case 3:
                    $list[$k]['type']='微信';
                    break;
            }
//            switch ($v['ti_status'] ){
//                case 0:
//                    $list[$k]['ti_status']='提现中';
//                    break;
//                case 1:
//                case 3:
//                    $list[$k]['ti_status']='提现成功';
//                    break;
//
//                case 2:
//                    $list[$k]['ti_status']='提现失败';
//                    break;
//
//            }
        }
        return json(['status' => 1, 'msg' => '获取数据成功','data'=>['list'=>$list,'total'=>$count]]);
    }

    /*资金流水*/
    public function money_log(){
        $user=session('user');
        $model=new MoneyLog();
        $tra=new Withdrawal();
        $usermodel=new UserModel();
        $poundage=Db::name('tpsoretype')->where(['id'=>$this->param['sid']])->value('poundage');
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $list=$model->field('id,type2,type,agentid,content,agent_price,addtime,type3,withdrawal_id')->where(['uid'=>$user['id'],'sid'=>$this->param['sid']])->limit($row)->order('addtime desc')->page($p)->order('addtime desc')->select();

        foreach ($list as &$v){


            $v['poundage']=$v['agent_price']*$poundage/100;
            switch ($v['type3']){
                case 0:
                    $v['type3']='一级好友';
                    $v['agentid']=$usermodel->where('id',$v['agentid'])->value('username');
                    break;
                case 1 :
                    $v['type3']='二级好友';
                    $v['agentid']=$usermodel->where('id',$v['agentid'])->value('username');
                    break;
                case 2:
                    $v['type3']='下级代理';
                    $v['agentid']=$usermodel->where('id',$v['agentid'])->value('username');
                    break;
                case 3:
                    $v['type3']='支付宝';
                    $v['agentid']=$tra->where('id',$v['withdrawal_id'])->value('ali_phone');
                    break;
                case 4:
                    $v['type3']='银行卡';
                    $v['agentid']=$tra->where('id',$v['withdrawal_id'])->value('bank_number');
                    break;
                case 5:
                    $v['type3']='微信';
                    $v['agentid']='暂不支持';
                    break;
            }
            unset($v['withdrawal_id']);

        }
        $count=$model->where(['uid'=>$user['id'],'sid'=>$this->param['sid']])->count();
        return json(['status' => 1, 'msg' => '获取数据成功','data'=>['list'=>$list,'total'=>$count]]);
    }

    /*获取百分比*/
    public function get_poundage(){
        $poundage=Db::name('tpsoretype')->field('poundage,min_poundage')->where(['id'=>$this->param['sid']])->find();
        return json(['status' => 1, 'msg' => '获取数据成功','data'=>$poundage]);
    }


}