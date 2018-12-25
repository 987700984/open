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

class Mycommission extends Controller
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

        $limit = 100;
        $param['pageNumber'] =1;
        $offset = ($param['pageNumber'] - 1) * $limit;
            $sql="call j21_myagentrebate(".$id.",'',".$limit.",".$offset.")";
            $selectResult=Db::query($sql);
            if(count($selectResult)>1){
                $countrebate = 0;
                foreach($selectResult[0] as $key=>$vo){
                    $countrebate = $countrebate+=$vo['rebate'];
                }
            $this->assign('countrebate',$countrebate);
            }else{
            $this->assign('countrebate',0.00);    
            }
        
        return $this->fetch();
    }
    

}
