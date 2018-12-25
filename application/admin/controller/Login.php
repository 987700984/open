<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <123464630@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Log;
use app\admin\model\UserType;
use think\Controller;
use org\Verify;
use think\Db;
use app\market\model\Market;

class Login extends Controller
{
    //登录页面
    public function index()
    {

        return $this->fetch('/login');
    }

    //登录操作
    public function doLogin()
    {
        $username = input("param.username");
        $password = input("param.password");
        $code = input("param.code");

        $result = $this->validate(compact('username', 'password', "code"), 'AdminValidate');
        if(true !== $result){
            return json(['code' => -5, 'data' => '', 'msg' => $result]);
        }

        $verify = new Verify();
        if (!$verify->check($code)) {
            return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
        }
        $i = 0;
        $hasUser = db('tpuser')->where('username', $username)->find();
        if (!$hasUser) {
            $hasUser = db('tpagent')->where('username', $username)->find();
            session('soretype',unserialize($hasUser['level']));
            $i = 1;

        }

        if(empty($hasUser)){
            return json(['code' => -1, 'data' => '', 'msg' => '管理员不存在']);
        }
        if(md5($password) != $hasUser['password']){
            return json(['code' => -2, 'data' => '', 'msg' => '密码错误']);
        }

        if(1 != $hasUser['status']){
            return json(['code' => -6, 'data' => '', 'msg' => '该账号被禁用']);
        }

        //获取该管理员的角色信息
        $user = new UserType();
        $info = $user->getRoleInfo($hasUser['typeid']);
        
        session('username', $username);
        session('id', $hasUser['id']);
        session('pic', $hasUser['pic']);
        session('role', $info['rolename']);  //角色名
        session('rule', $info['rule']);  //角色节点
        session('action', $info['action']);  //角色权限
        $this->logData=[
            'uid'=>session('id'),
            'name'=>session('username'),
            'aouth'=>session('role'),
            'ip'=>getIp(),
            'addtime'=>time(),

        ];
        $this->log=new Log();
        $this->log->addLog($this->logData,'进行了登录糖果后台操作');
        //更新管理员状态
        $param = [
            'loginnum' => $hasUser['loginnum'] + 1,
            'last_login_ip' => request()->ip(),
            'last_login_time' => time()
        ];
        if (!$i) {
            db('tpuser')->where('id', $hasUser['id'])->update($param);
        }

        return json(['code' => 1, 'data' => url('index/index'), 'msg' => '登录成功']);
    }


    //注册
    public function reg(){
        return $this->fetch('/reg2');
    }

    //手机验证码
    public function code(){

        $user = new UserType();
        $code = $user->code();

    }

    //图片上传
    public function upload(){
        $param = input('post.');
        $user = new UserType();

        $param = parseParams($param['data']);
        $file = request()->file('file');

        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
                $pic = $user->moveOSS($info->getFilename(), $info->getSaveName());
                $param['pic'] = $pic;
            }else{
                // 上传失败获取错误信息
                // echo $file->getError();
            }
        }
        if ($param['pic']) {
            return json(['code' => '1', 'type' => $param['type'], 'pic' => $param['pic'], 'msg' => '上传成功']);
        }else{
            return json(['code' => '-4', 'data' => '', 'msg' => '上传失败']);

        }
    }

    //申请代理
    public function agent(){
        $user     = new UserType();
        $param    = input('post.');
        $pic      = $param['pic'];
        $param    = parseParams($param['data']);
        
        //判断用户名重复
        $username = $user->agentName($param['username']);
        if ($username) {
            return json(['code' => '-4', 'data' => '', 'msg' => '该用户名已存在']);
            
        }
        
        //验证手机号
        $phone    = $user->agentPhone($param['phone']);
        if ($phone) {
            return json(['code' => '-4', 'data' => '', 'msg' => '该手机号码已申请']);
            
        }

        //验证验证码
        $code = $param['code'];
        $result   = $user->agentcode($param['phone'], $code);
        if (!$result) {
            return json(['code' => '-4', 'data' => '', 'msg' => '验证码错误']);
        }


        unset($param['code']);
        $param['password'] = md5($param['password']);
        $param['addtime']  = time();
        $param['picid']    = $user->picinsert($pic);
        
        $res   = $user->agentApply($param, $code);
        if ($res) {
            return json(['code' => '1', 'data' => '', 'msg' => '申请成功']);
            
        }else{
            return json(['code' => '-4', 'data' => '', 'msg' => '申请失败']);

        }
    }


    //验证码
    public function checkVerify()
    {
        $verify = new Verify();
        $verify->imageH = 32;
        $verify->imageW = 100;
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }

    //退出操作
    public function loginOut()
    {
        session(null);

        $this->redirect(url('index'));
    }

    //检测userid是否存在
    public function checkuserid(){
        if(request()->isPost()){
            $param = input('post.');
            $userid = $param['userid'];
            if(empty($userid)){
               return json(['code' => -4, 'data' => '', 'msg' => 'userid不能为空']); 
            }
            
            $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';        
            $sql="select * from t_user where userid='".$userid."'";
            $tuserinfo =  Db::connect($connection)->query($sql);
            if(empty($tuserinfo)){
                return json(['code' => -4, 'data' => '', 'msg' => '找不到该userid']); 
            }else{
                return json(['code' => 1, 'data' => '', 'msg' => $tuserinfo[0]['nick_name']]);
            }
        }
              
    }

    //计算折扣
    public function caldiscount(){
        if(request()->isPost()){
            $param = input('post.');
            $discount = $param['discount'];
            $cardcount = $param['cardcount'];
            $id = session('id');
            if(empty($cardcount)){
               return json(['code' => -4, 'data' => '', 'msg' => '请填写房卡数量']); 
            }
            if(!is_numeric($cardcount)){
               return json(['code' => -4, 'data' => '', 'msg' => '请填写正确的房卡数量']); 
            }
            //单张房卡价格
            $cardprice = Db::name('tpgoods')->where('goodsid',1)->find();
            $goodsprice = $cardprice['goodsprice'];

            //原折扣的价格
            $tpuserinfo = Db::name('tpuser')->where('id',$id)->find();
            $tpcommissionsinfo = Db::name('tpcommissions')->where('id',$tpuserinfo['typeid'])->find();
            $commissionsmodperson =$tpcommissionsinfo['commissionscardpaydiscount']/100;
            $countprice = ($cardcount*$goodsprice)*$commissionsmodperson;

            //按现在折扣的价格
            $nowtprice = ($cardcount*$goodsprice)*$discount/100;
            return json(['code' => 1, 'data' => '', 'msg' => $nowtprice-$countprice]);


 

        }        
    }

    /**
     * 更新单边上扬
     * @return [type] [description]
     */
    public function updateMarket(){
        $coins = DB::name('market_config')->select();

        foreach ($coins as $key => $value) {
            $model = new Market();
            $res = $model->where(['coin'=>$value['coin']])->order('create_time desc')->limit(1)->select();
            if($res){
                $res = $res[0]->toArray();
            }else{
                $res['price'] = $value['money'];
            }

            $change = explode(',',$value['change']);
//             dump($change);
            $up = rand($change[0]*100,$change[1]*100)/100;
            //echo $value['coin'].'='.$up.'</br>';
            // dump($res[0]->toArray());
            // $price = 1;
            $market = Market::create([
                'coin'  =>  $value['coin'],
                'price' =>  $res['price']+$up,
                'change' => round($up/$res['price'],4)
            ]);            
        }

        echo date('Y-m-d H:i:s',time())."更新成功\n";
        // $market = Market::create([
        //     'coin'  =>  'thinkphp',
        //     'price' =>  '1'
        // ]);
    }

    /**
     * 更新用户等级
     * @return [type] [description]
     */
    public function updateUserType()
    {
        set_time_limit(0); //定时任务不超时

        //更新最大积分
       // Db::query("UPDATE ims_tpintegral SET max_integral = integral WHERE max_integral < integral");

        $level = Db::name('tpsoreLevel')->order('sid,min')->select();

        foreach($level as $key => $value){
            $data['lid'] = $value['lid'];
            $data['level'] = $value['name'];

            if(isset($level[$key+1]) && $value['sid'] == $level[$key+1]['sid']){
                $num = Db::name('tpintegral')->where('integral','between',[$level[$key]['min'],$level[$key+1]['min']])->update($data);
                echo $value['sid'] .':'.$value['name'].'更新'.$num.'个\n';
            }else{
                $num = Db::name('tpintegral')->where('integral','>=',$level[$key]['min'])->update($data);
                echo $value['sid'] .':'.$value['name'].'更新'.$num.'个\n';
            }
        }
        echo date('Y-m-d H:i:s',time())."更新成功\n";
    }

    /**
     * 计算利息
     */
    public function setIerest()
    {
        set_time_limit(0); //定时任务不超时
        $role = Db::name('tpsoreLevel')->where('interest','>',0)->order('min desc')->select();

        $all = Db::name('tpintegral')->count();
        $row = 200;
        $allpage = ceil($all/$row);

        for($i=0;$i<$allpage;$i++){
            $data = Db::name('tpintegral')
                ->field('id,sid,uid,integral')
                //->order('integral desc')
                ->limit($i*$row,$row)->select();

            foreach ($data as $key=>$value){
                //发放利息
                foreach ($role as $k=>$v){
                    if($value['sid'] == $v['sid'] && $value['integral'] >= $v['min']){
                        $level = $v;

                        //计算利息
                        $lixi = $value['integral']*$level['interest']/10000;
                        echo 'ID:'.$value['id']." user:".$value['uid'].' sid:'.$value['sid'].' lixi:'.$lixi."\n";
                        //发利息
                        Db::name('tpintegral')->where(['id'=>$value['id']])->update([
                            'integral'=>['exp','integral+'.$lixi],
                            'all_bonus'=>['exp','all_bonus+'.$lixi]
                        ]);
                        //记流水
                        Db::name('tpbill')->insert([
                            'sid'=>$value['sid'],
                            'uid'=>$value['uid'],
                            'addtime'=>time(),
                            'content'=>'每日发放利息',
                            'type'=>0,
                            'type2'=>8,
                            'num'=>$value['integral'],
                            'price'=>$lixi
                        ]);

                        break;
                    }
                }

            }
        }

        echo date('Y-m-d H:i:s',time())."更新成功\n";
    }


    //编辑器图片上传
    public function urlupload(){
        $file = request()->file('imgFile');
        $user = new UserType();

        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
                $pic = $user->moveOSS($info->getFilename(), $info->getSaveName());
                return json(['error'=>0,'url'=>$pic]);
            }else{
                // 上传失败获取错误信息
                return json(['error'=>1,'message'=>$file->getError()]);
            }
        }else{
            return json(['error'=>1,'message'=>'文件不存在']);
        }

    }
}