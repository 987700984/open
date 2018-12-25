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
use app\settings\model\syncusersModel;
use app\settings\model\userModel;

class Usercenter extends Controller
{

	/*
    public function index()
    {	
    	$parentid = input('param.parentid');
    	if(!empty($parentid)){
    		$parentid = $parentid;
    	}else{
    		$parentid = 0;
    	}
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
                $this->redirect(url('Usercenter/index'));
                }                
            }
	        $wechat = $jsonrt['openid'];
	        $unionid = $jsonrt['unionid'];
	        //根据openid获取微信用户信息
	        $wecurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$wechat.'&lang=zh_CN ';
	        $wechatinfojson = curlGet($wecurl);
	        $wechatinfo = json_decode($wechatinfojson,true);
	        $connection = 'mysql://adminroot:f8uYciEXSV@rm-wz99sj9293772q45io.mysql.rds.aliyuncs.com:3306/qipai#utf8';
            $sql="select * from t_user where wechat='".$wechat."'";
            $selectResult =  Db::connect($connection)->query($sql);
            $imstpuserinfo = Db::name('tpuser')->where('openid',$wechat)->find();

            $Syncusers = new syncusersModel();
        	$userModel=new  userModel();
        	//流程 如果游戏后台有该微信用户信息，2+1后台没有。把游戏后台的数据同步到2+1后台
        	//如果两边数据都为空，把仅把数据添加到2+1后台
        	//如果2+1后台有数据，仅把数据读取出来，不作操作
            if(!empty($selectResult) && empty($imstpuserinfo)){
	           	foreach ($selectResult as $key => $value){
	            	if($value['userid']==0){
	            		continue;
	            	}
	            	$inReturn=$Syncusers->insertT_user($value);
	            	//同步数据
	            	if($inReturn['code']==0){
	            		$userIn['id']=null;
	            		$userIn['username']=$value['nick_name'];
	            		$userIn['password']=$value['password'];
	            		$userIn['loginnum']=0;
	            		$userIn['last_login_ip']=0;
	            		$userIn['last_login_time']=time();
	            		$userIn['status']=1;
	            		$userIn['typeid']=7;//默认角色
	            		$userIn['parentid']=$parentid;
	            		$userIn['cardcount']=$value['card_count'];
	            		$userIn['userid']=$value['userid'];
	                    $userIn['openid']=$value['wechat'];
	            
	            		$userModel->insertIms_tpuser($userIn);
	            		
	            	}
	            	$tpuserid = Db::name('tpuser')->getLastInsID();
	            	$imstpuserinfo = Db::name('tpuser')->where('id',$tpuserid)->find();
	            }
            }elseif(!empty($selectResult) && !empty($imstpuserinfo)){
	           	foreach ($selectResult as $key => $value){
	            	if($value['userid']==0){
	            		continue;
	            	}
	            	$inReturn=$Syncusers->insertT_user($value);
	            	//同步数据
	            	if($inReturn['code']==0){
	            		$userIn['id']=null;
	            		$userIn['username']=$value['nick_name'];
	            		$userIn['password']=$value['password'];
	            		$userIn['loginnum']=0;
	            		$userIn['last_login_ip']=0;
	            		$userIn['last_login_time']=time();
	            		$userIn['status']=1;
	            		$userIn['typeid']=7;//默认角色
	            		$userIn['parentid']=$parentid;
	            		$userIn['cardcount']=$value['card_count'];
	            		$userIn['userid']=$value['userid'];
	                    $userIn['openid']=$value['wechat'];
	            
	            		$userModel->updateIms_tpuser($userIn);
	            		
	            	}
	            	
	            }
            }elseif (empty($selectResult) && empty($imstpuserinfo)) {
            			//添加数据到2+1后台
	            		$tpuserIn['id']=null;
	            		$tpuserIn['username']=$wechatinfo['nickname'];
	            		$tpuserIn['loginnum']=0;
	            		$tpuserIn['last_login_ip']=0;
	            		$tpuserIn['last_login_time']=time();
	            		$tpuserIn['status']=1;
	            		$tpuserIn['typeid']=0;//默认角色
	            		$tpuserIn['parentid']=$parentid;
	            		$tpuserIn['cardcount']=0;
	            		$tpuserIn['userid']=0;
	                    $tpuserIn['openid']=$wechat;
	                    $tpuserIn['unionid']=$unionid;
	            		$userModel->insertIms_tpuser($tpuserIn);

	            		$tpuserid = Db::name('tpuser')->getLastInsID();
	            		$imstpuserinfo = Db::name('tpuser')->where('id',$tpuserid)->find();
            }else{

            }
            session('openid', $jsonrt['openid']);
            session('unionid', $jsonrt['unionid']);
	        //会员级别
	        $tproleinfo = Db::name('tprole')->where('id',$imstpuserinfo['typeid'])->find();
	        $this->assign('tproleinfo',$tproleinfo);
	        $this->assign('headimgurl',$wechatinfo['headimgurl']);
	        $this->assign('nickname',$wechatinfo['nickname']);
	       	$this->assign('imstpuserinfo',$imstpuserinfo);            
	    	return $this->fetch();
    	}



    }
    */

    public function index()
    {	
    	$parentid = input('param.parentid');
    	if(!empty($parentid)){
    		$parentid = $parentid;
    	}else{
    		$parentid = 0;
    	}
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
                $this->redirect(url('Usercenter/index'));
                }                
            }
	        $openid = $jsonrt['openid'];
	        $unionid = $jsonrt['unionid'];
	    }
	   	$wecurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$openid.'&lang=zh_CN ';
	    $wechatinfojson = curlGet($wecurl);
	    $wechatinfo = json_decode($wechatinfojson,true); 
	    session('openid',$openid);
        session('unionid',$unionid);
        $tpuserinfo = Db::name('tpuser')->where('openid',$openid)->find();
        $url = url('Login/login');
        if(empty($tpuserinfo)){
        	session('parentid',$parentid);
        	$this->redirect($url);exit;  
        }else{
        	session('id', $tpuserinfo['id']);
        }
	    //会员级别
	    $tproleinfo = Db::name('tprole')->where('id',$tpuserinfo['typeid'])->find();
	    $this->assign('tproleinfo',$tproleinfo);
	    $this->assign('headimgurl',$wechatinfo['headimgurl']);
	    $this->assign('nickname',$wechatinfo['nickname']);
	    $this->assign('imstpuserinfo',$tpuserinfo);            
	   	return $this->fetch();

	}  	


}
