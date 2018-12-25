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
namespace app\proxy\controller;

use app\admin\controller\Base;;

use app\proxy\model\proxyModel;
// use app\goods\model\goodsModel;
use think\Db;

class Proxy extends Base
{
    public function index()
    {
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '';
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where .= ' and s.name like "%'.$param['searchText'].'%"';
            }
            $proxy = new proxyModel();
            $id = $proxy->agent();
            if ($id) {
            	$where .= ' and uid='.$id;
            }
            $selectResult = $proxy->getproxyByWhere($where, $offset, $limit);
            // var_dump($selectResult);exit;
            // $proxystatus=config('proxystatus');
            // $orderstype=config('orderstype');
            $arr = array('未审核', '审核通过', '审核未通过');
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){ 
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);  
                    $selectResult[$key]['starttime'] = date('Y-m-d H:i:s', $vo['starttime']);  
                    $selectResult[$key]['endtime'] = date('Y-m-d H:i:s', $vo['endtime']);  
                    if ($vo['status']) {

	                    $operate = [
	                        '编辑' => url('proxy/edit', ['id' => $vo['id']]),
	                        '删除' => "javascript:del('".$vo['id']."')"

	                    ];
                    }else{
                    	$operate = [
	                        '通过' => "javascript:save('".$vo['id']."', 1)",
	                        '不通过' => "javascript:save('".$vo['id']."', 2)"

	                    ];
                    }
                   
                    $selectResult[$key]['status'] = $arr[$vo['status']]; 
                          
                    $selectResult[$key]['operate'] = showOperate($operate);   
                    // $selectResult[$key]['status']=$proxystatus[$vo['status']];
                    // $selectResult[$key]['proxytype']=$proxytype[$vo['proxytype']];
                }               
                // var_dump($selectResult);exit;
                $return['total'] = $proxy->getAllproxy($where);
                $return['rows'] = $selectResult;
            	return json($return);
            }
        }
        return $this->fetch();
    }

    public function add(){
    	if (request()->isPost()) {
            $proxy 				= new proxyModel();
            $param 				= input('param.');  
            $param 		  		= parseParams($param['data']);
            $param['uid'] 		= $_SESSION['think']['id'];
            $param['addtime'] 	= time();
            $param['starttime'] = strtotime($param['starttime']);
            $param['endtime'] 	= strtotime($param['endtime']);
    		$flag 				= $proxy->proxyAdd($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

    	}
    	return $this->fetch();
    }

    public function edit()
    {
        $proxy   = new proxyModel();

    	if (request()->isPost()) {
    		$param 				= input('param.');  
            $param 		  		= parseParams($param['data']);
            $param['addtime'] 	= time();
            $param['starttime'] = strtotime($param['starttime']);
            $param['endtime'] 	= strtotime($param['endtime']);
            $flag 				= $proxy->proxyEdit($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    	}else{
    		$id  = input('param.id');
    		$res = $proxy->oneproxy($id);
    		$arr = array(1 => '审核通过', '审核失败');
    		$this->assign(['id' => $res['id'], 'name' =>$res['name'], 'starttime' => date('Y-m-d H:i:s', $res['starttime']), 'endtime' => date('Y-m-d H:i:s', $res['endtime']), 'num' => $res['num'], 'status' => $res['status'], 'ratio' => $res['ratio']]);
    		return $this->fetch();
    	}
    }

    public function save()
    {
        $proxy   = new proxyModel();
		$get = input('get.');
		$param['id']     = $get['id'];
		$param['status'] = $get['status'];
		if ($param['status'] == 1) {
			$proxy->setKey($param['id']);
		}
		$flag            = $proxy->proxyEdit($param);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function del()
    {
    	$proxy   = new proxyModel();
    	$id = input('get.id');
		$flag            = $proxy->delproxy($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

}
