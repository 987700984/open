<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2099 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use app\agent\model\agentModel;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\agent\model\TpordersModel;

class Agent extends Controller
{
    public function index()
    {	
        $id = session('id');
        if(empty($id)){
            return $this->error('参数错误',url('Beagent/index'));
        }
        $imstpuserinfo = Db::name('tpuser')->where('id',$id)->find();
        if(empty($imstpuserinfo)){
            return $this->error('帐号不存在',url('Beagent/index'));
        }
        $username = '';
        $status = config('user_status');
        $type=config('role_type');
        $rank=config('user_rank');
        $limit = 10;
        $param['pageNumber'] =1;
        $offset = ($param['pageNumber'] - 1) * $limit;

        $sql="call j21_myagent(".$id.",'".$username."',".$limit.",".$offset.")";
        $selectResult=Db::query($sql);
        if(count($selectResult)>1){
                foreach($selectResult[0] as $key=>$vo){
                    $selectResult[0][$key]['status']=$status[$vo['status']];
                    $selectResult[0][$key]['rolename']=$type[$vo['rolename']];
                    $selectResult[0][$key]['rank2or3']=$rank[$vo['rank2or3']];
                }
                
                $return['total'] = $selectResult[1][0]['total'];
                $return['rows'] = $selectResult[0];
            $this->assign('agentlist',$return['rows']); 
            }else{
            $this->assign('agentlist','');     
            }
           
        return $this->fetch();
    }

    //我的代理详情页
    public function agentshow(){
        $agentid = input('param.id');
        if(empty($agentid) || !is_numeric($agentid)){
            return $this->error('参数错误',url('Agent/index'));
        }
        $imstpuserinfo = Db::name('tpuser')->where('id',$agentid)->find();
        if(empty($imstpuserinfo)){
            return $this->error('帐号不存在',url('Agent/index'));
        }
        $tproleinfo = Db::name('tprole')->where('id',$imstpuserinfo['typeid'])->find();
        $parentid = $imstpuserinfo['parentid'];
        if($parentid==session('myagentid')){
            $imstpuserinfo['rank2or3'] = '二级';  
        }else{
            $imstpuserinfo['rank2or3'] = '三级';
        }
        if($imstpuserinfo['status']==1){
            $imstpuserinfo['status'] = '正常';
        }else{
            $imstpuserinfo['status'] = '禁止登录';
        }
        $imstpuserinfo['rolename'] = $tproleinfo['rolename'];
        $this->assign('imstpuserinfo',$imstpuserinfo);   
        return $this->fetch();
    }
    

}
