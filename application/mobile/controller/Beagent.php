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

namespace app\mobile\controller;

use think\Controller;
use think\Db;
use org\Verify;
use app\settings\model\syncusersModel;
use app\settings\model\userModel;

class Beagent extends Controller
{

	//申请成为代理页面
    /* 修改了成为代理流程，不需要走微信接口获取微信信息，故注释(已丢弃)
    public function index()
    {
        if(empty(input('get.code'))){
            $customeUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.config('Wxkey.APPID').'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
             $this->redirect($oauthUrl);exit;       
        }else{
            $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.config('Wxkey.APPID').'&secret='.config('Wxkey.SECRET').'&code='.input('get.code').'&grant_type=authorization_code';   
            $return = curlGet($tokenUrl);
            $jsonrt = json_decode($return,true);
            //防止刷新页面时，code失效报错
            if(empty($jsonrt['unionid'])){
                if($jsonrt['errcode']==40163){
                $this->redirect(url('Beagent/index'));
                }                
            }
            $wechat = $jsonrt['openid'];
            $unionid = $jsonrt['unionid'];
            //根据openid获取微信用户信息
            $wecurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$wechat.'&lang=zh_CN ';
            $wechatinfojson = curlGet($wecurl);
            $wechatinfo = json_decode($wechatinfojson,true);
            $imstpuserdailiinfo = Db::name('tpuser')->where('openid',$wechat)->where('typeid','in','8,11,12,13')->find();
        }
        if(!empty($imstpuserdailiinfo)){
            $this->redirect(url('Usercenter/index'));exit;
        }else{
            $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';
            $sql="select * from t_user where wechat='".$wechat."'";
            $selectResult =  Db::connect($connection)->query($sql);
            $imstpuserinfo = Db::name('tpuser')->where('openid',$wechat)->find();
            $Syncusers = new syncusersModel();
            $userModel=new  userModel();
                if(!empty($selectResult) && empty($imstpuserinfo)){
                    foreach ($selectResult as $key => $value){
                        if($value['userid']==0){
                            continue;
                        }
                        $inReturn=$Syncusers->insertT_user($value);
                        if($inReturn['code']==0){
                            $userIn['id']=null;
                            $userIn['username']=$value['nick_name'];
                            $userIn['password']=$value['password'];
                            $userIn['loginnum']=0;
                            $userIn['last_login_ip']=0;
                            $userIn['last_login_time']=time();
                            $userIn['status']=1;
                            $userIn['typeid']=7;//默认角色
                            $userIn['parentid']=0;
                            $userIn['cardcount']=0;
                            $userIn['userid']=$value['userid'];
                            $userIn['openid']=$value['wechat'];
                            
                            $userModel->insertIms_tpuser($userIn);
                        }
                    }
                }elseif (empty($selectResult) && empty($imstpuserinfo)) {
                            $tpuserIn['id']=null;
                            $tpuserIn['username']=$wechatinfo['nickname'];
                            $tpuserIn['loginnum']=0;
                            $tpuserIn['last_login_ip']=0;
                            $tpuserIn['last_login_time']=time();
                            $tpuserIn['status']=1;
                            $tpuserIn['typeid']=0;//默认角色
                            $tpuserIn['parentid']=0;
                            $tpuserIn['cardcount']=0;
                            $tpuserIn['userid']=0;
                            $tpuserIn['openid']=$wechat;
                            $tpuserIn['unionid']=$unionid;
                            $userModel->insertIms_tpuser($tpuserIn);
                }else{
                    $tprolelist = Db::name('tprole')->where('id','in','8,11,12,13')->select();
                    $this->assign('openid',$wechat);
                    $this->assign('unionid',$unionid);
                    $this->assign('nickname',$wechatinfo['nickname']);
                    $this->assign('tprolelist',$tprolelist);
                    return $this->fetch();
                }
             
        }

		
    }
*/

    //微信端注册用户页面
    public function index(){
        if(empty(input('get.code'))){
            $customeUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.config('Wxkey.APPID').'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
             $this->redirect($oauthUrl);exit;       
        }else{
            $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.config('Wxkey.APPID').'&secret='.config('Wxkey.SECRET').'&code='.input('get.code').'&grant_type=authorization_code';   
            $return = curlGet($tokenUrl);
            $jsonrt = json_decode($return,true);
            //防止刷新页面时，code失效报错
            if(empty($jsonrt['unionid'])){
                if($jsonrt['errcode']==40163){
                $this->redirect(url('Beagent/index'));
                }                
            }
            $openid = $jsonrt['openid'];
            $unionid = $jsonrt['unionid'];
            //根据openid获取微信用户信息
            $wecurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$openid.'&lang=zh_CN ';
            $wechatinfojson = curlGet($wecurl);
            $wechatinfo = json_decode($wechatinfojson,true);
        }
        //根据openid是存在，如果存在直接跳转到微新代理后页面
        $tpuserinfo = Db::name('tpuser')->where('openid',$openid)->find();
        $url = url('Usercenter/index');
        if(!empty($tpuserinfo)){
           $this->redirect($url);exit;  
        }
        //代理级别
        $tprolelist = Db::name('tprole')->alias('r')->field('r.id,r.rolename,g.goodsprice')->join('ims_tpgoods g','r.id = g.roleid','LEFT')->where('id','in','7,8,11,12,13,14')->order('id')->select();
        $this->assign('openid',$openid);
        $this->assign('unionid',$unionid);
        $this->assign('nickname',$wechatinfo['nickname']);
        $this->assign('tprolelist',$tprolelist);
        return $this->fetch();
    }



    //微信端注册用户页面提交
    public function addagent(){
        if(request()->isPost()){
                $param = input('param.');
                $param = parseParams($param['data']);
                $phone = $param['phone'];
                $pyzm = $param['pyzm'];
                $typeid = $param['typeid'];
                $userid = $param['userid'];
                $password = $param['password'];
                $passwordtwo = $param['passwordtwo'];

                $area = '';
                $area.= $param['province'];
                if(!empty($param['city'])){
                    $area.='-'.$param['city'];
                }
                if(!empty($param['area'])){
                 $area.='-'.$param['area'];   
                }
                $address = $param['address'];
                $service = $param['service'];
                $openid = $param['openid'];
                $unionid = $param['unionid'];
                $nickname = $param['nickname'];

                if(empty($password)){
                    return json(['code' => -4, 'data' => '', 'msg' => '密码不能为空']); 
                }
                if($password != $passwordtwo){
                    return json(['code' => -4, 'data' => '', 'msg' => '两次密码输入不一致']);
                }


                $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';
                $sql="select * from t_user where userid='".$userid."'";
                $tuserinfo =  Db::connect($connection)->query($sql);
                if(empty($tuserinfo)){
                   return json(['code' => -4, 'data' => '', 'msg' => '找不到该userid']); 
                }
                /*
                $verify = new Verify();
                if (!$verify->check($param['yzm'])) {
                    return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
                }
                */
                if (empty($param['pyzm'])) {
                    return json(['code' => -4, 'data' => '', 'msg' => '手机验证码不能为空']);
                }
                $msginfo = Db::name('msg')->where('phone',$phone)->where('type',1)->order('addtime desc')->find();
                if (empty($msginfo) || $msginfo['msgcode']!=$pyzm) {
                    return json(['code' => -4, 'data' => '', 'msg' => '手机验证码输入错误']);
                }
                if (empty($msginfo) || $msginfo['msgcode']!=$pyzm) {
                    return json(['code' => -4, 'data' => '', 'msg' => '手机验证码输入错误']);
                }

                if (time()-$msginfo['addtime']>300) {
                    return json(['code' => -4, 'data' => '', 'msg' => '您的验证码已过期']);
                }

                //查找userid这条数据是否已经存在
                $tpuserinfo = Db::name('tpuser')->where('userid',$userid)->find();
                if(!empty($tpuserinfo)){
                    session('username',$tpuserinfo['username']);
                    session('openid',$openid);
                   return json(['code' => -9, 'data' => url('Login/login'), 'msg' => 'userid已存在,请直接登录']); 
                }


                $Syncusers = new syncusersModel();
                $userModel=new  userModel();
                if(!empty($tuserinfo)){
                    foreach ($tuserinfo as $key => $value){
                        if($value['userid']==0){
                            continue;
                        }
                        $inReturn=$Syncusers->insertT_user($value);
                        //同步数据
                        if($inReturn['code']==0){
                            $userIn['id']=null;
                            $userIn['username']=$nickname;
                            $userIn['password']=MD5($password);
                            $userIn['loginnum']=0;
                            $userIn['last_login_ip']=0;
                            $userIn['last_login_time']=time();
                            $userIn['status']=1;
                            $userIn['typeid']=$typeid;
                            $userIn['parentid']=session('parentid');
                            $userIn['cardcount']=$value['card_count'];
                            $userIn['userid']=$value['userid'];
                            $userIn['openid']=$openid;
                            $userIn['unionid']=$unionid;

                            $j21existsuserid=Db::name('tpuser')->where('userid',$value['userid'])->find();
                            if (!empty($j21existsuserid)){
                                $userIn['id']=$j21existsuserid['id'];
                                Db::name('tpuser')->where('id',$j21existsuserid['id'])->update($userIn);
                                $tpuserid = $j21existsuserid['id'];
                            }else{
                              $tpuserid = Db::name('tpuser')->insertGetId($userIn);  
                              //得到上级，如果上级是角色是会员，往上级账户充值房卡数量，并记录流水  
                              $parentid = session('parentid');
                              if($parentid){
                                  $tpuserparent = Db::name('tpuser')->where('id',$parentid)->find();
                                  if($tpuserparent['typeid'] == 7 and !empty($tpuserparent)){
                                    //奖励房卡数量
                                    $extensionnum = config('extensionnum');
                                    //根据userid更改游戏服务器房卡数量
                                    Db::connect($connection)->execute('update t_user set card_count=card_count+:card_count where userid=:userid ',['userid'=>$tpuserparent['userid'],'card_count'=>$extensionnum]);

                                    //修改后台服务器房卡数量，记录流水
                                    Db::name('tpuser')->where('id',$parentid)->setInc('cardcount',$extensionnum);
                                    $data = [
                                        'goodsid' => 1,
                                        'orderscreatetime' => date('Y-m-d H:i:s'),
                                        'orderscreatepersonid' =>$tpuserid,
                                        'ordersstatus' => 0,
                                        'ordersquantity' => $extensionnum,
                                        'orderstype' => 1,
                                        'ordersallotpersonid' => $parentid,
                                    ];
                                    //后台流水
                                    Db::name('tporders')->insertGetId($data);
                                    //游戏后台流水
                                    Db::connect($connection)->execute('insert into t_user_card_change(id,userid,source,card_num,create_time) values(uuid(),:userid,:source,:card_num,:create_time)',['userid'=>$tpuserparent['userid'],'source'=>'奖励','card_num'=>$extensionnum,'create_time'=>date('Y-m-d H:i:s')]);

                                  }                                
                              }



                            }
                            
                        }
                    }
                }
                if($tpuserid){
                    Db::name('tpuser')->where('id',$tpuserid)->update(['phone' => $phone]);
                    $data = ['wechat_name'=>$nickname,'typeid'=>$typeid,'area'=>$area,'address'=>$address,'service_address'=>$service,'addtime'=>time(),'openid'=>$openid,'unionid'=>$unionid,'tpuserid'=>$tpuserid,'userid'=>$userid];
                    $tpuser_agentresult = Db::name('tpuser_agent')->insert($data);
                    if($tpuser_agentresult){
                        return json(['code' => 1, 'data' => url('Usercenter/index'), 'msg' => '提交成功！']);
                    }else{
                        return json(['code' => -4, 'data' => '', 'msg' => '系统繁忙，请稍后再试']);
                    }
                }else{
                    return json(['code' => -4, 'data' => '', 'msg' => '提交申请失败，请稍后再试']);
                }
        }        
    }


    public function recharge(){
        return $this->fetch();
    }
    //发送短信验证码
    public function sendmsg(){
        if(request()->isPost()){
            $phone = input('post.phone');
            $checkphone ='/^(1(([35][0-9])|(47)|[8][01256789]))\d{8}$/';
            if(empty($phone)){
               return json(['code' => -4, 'data' => '', 'msg' => '手机号码不能为空']); 
            }
            if (!preg_match($checkphone, $phone)){
                return json(['code' => -4, 'data' => '', 'msg' => '请输入正确的手机号码']);
            }
            $msgcode = randomnum(4);
            $type = 1;
            $addtime = time();
            $content = "您的短信验证码是".$msgcode.",请在5分钟内使用。";
            $getmsgurl = 'http://utf8.sms.webchinese.cn/?Uid='.config('Msgkey.user').'&Key='.config('Msgkey.pasd').'&smsMob='.$phone.'&smsText='.$content.'';
            $return = curlGet($getmsgurl);

            $data = ['phone'=>$phone,'msgcode'=>$msgcode,'type'=>$type,'addtime'=>$addtime,'content'=>$content];
            $msgid = Db::name('msg')->insert($data);
            if($return && $msgid){
               return json(['code' => 1, 'data' => '', 'msg' => '验证码发送成功,5分钟输入有效']); 
            }
           
        }
    }



}
