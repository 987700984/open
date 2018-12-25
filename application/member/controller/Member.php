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
namespace app\member\controller;

use app\admin\controller\Base;
use think\Db;

class Member extends Base
{
    //会员列表
    public function index()
    {
        if(request()->isAjax()){
        	        	
        	$param = input('param.');
        	$limit = $param['pageSize'];
        	$offset = ($param['pageNumber'] - 1) * $limit;//
        	
        	$id=session("id");
        	$username = '';
        	if (isset($param['searchText']) && !empty($param['searchText'])) {
        		$username = $param['searchText'];
        	}
        	$status = config('user_status');
        	$type=config('role_type');
        	$rank=config('user_rank');

        	$sql="call j21_mymember(".$id.",'".$username."',".$limit.",".$offset.")";
        	$selectResult=Db::query($sql);
        	if(count($selectResult[0])>0){
        		
        		foreach($selectResult[0] as $key=>$vo){
        			$selectResult[0][$key]['status']=$status[$vo['status']];
        			$selectResult[0][$key]['rolename']=$type[$vo['rolename']];
        			$selectResult[0][$key]['rank2or3']=$rank[$vo['rank2or3']];
        		}
        		
        		$return['total'] = $selectResult[1][0]['total'];
        		$return['rows'] = $selectResult[0];
        		return json($return);
        	}
        }
        return $this->fetch();
    }

    //我的返利
    public function rebate()
    {
    	if(request()->isAjax()){
    
    		$param = input('param.');
    		$limit = $param['pageSize'];
    		$offset = ($param['pageNumber'] - 1) * $limit;//
    		 
    		$id=session("id");
    		$username = '';
    		if (isset($param['searchText']) && !empty($param['searchText'])) {
    			$username = $param['searchText'];
    		}
    		$status = config('user_status');
    		$type=config('role_type');
    		$rank=config('user_rank');
    		$orderstype=config('orderstype');
    		$ordersstatus=config('ordersstatus');
    
    		$sql="call j21_mymemberrebate(".$id.",'".$username."',".$limit.",".$offset.")";
    		$selectResult=Db::query($sql);
    		if(count($selectResult[0])>0){
    
    			foreach($selectResult[0] as $key=>$vo){
    				$selectResult[0][$key]['status']=$status[$vo['status']];
    				$selectResult[0][$key]['rolename']=$type[$vo['rolename']];
    				$selectResult[0][$key]['rank2or3']=$rank[$vo['rank2or3']];
    				$selectResult[0][$key]['orderstype']=$orderstype[$vo['orderstype']];
    				$selectResult[0][$key]['ordersstatus']=$ordersstatus[$vo['ordersstatus']];
    			}
    
    			$return['total'] = $selectResult[1][0]['total'];
    			$return['rows'] = $selectResult[0];
    			return json($return);
    		}
    	}
    	return $this->fetch();
    }    
}
