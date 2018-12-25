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
namespace app\orders\controller;

use app\admin\controller\Base;

use app\orders\model\ordersModel;
use app\orders\model\goodsModel;
use think\Db;

class Orders extends Base
{
    public function index()
    {
        $ordertype = Db::name('tporderType')->select();

        if(request()->isAjax()){

            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $type = input('type');

            $where = 1;
            if($type){
                $where1 = 'type='.$type;
            }else{
                $where1 = 1;
            }
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where = '(oid="'.$param['searchText'].'" or phone="'.$param['searchText'].'")';
            }
            if(isset($param['forr']) && !empty($param['forr'])){
                $where .= ' and forr='.$param['forr'];
            }

            if($param['state'] != 'all'){
                $where .= ' and state='.$param['state'];
            }
            $orders = new ordersModel();
            $selectResult = $orders->where($where1)->where($where)->order('state desc,id desc')->limit($offset,$limit)->select();

            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){	
                    $selectResult[$key] = $vo->toArray();
            		$operate = [
                        '已发货' =>"javascript:ordersEdit('".$vo['id']."')",
            			// '编辑' => url('orders/ordersEdit', ['id' => $vo['id']]),
            			// '删除' => "javascript:ordersDel('".$vo['id']."')"
            		];           	
            		$selectResult[$key]['operate'] = showOperate($operate);
                    $selectResult[$key]['goods'] = '<a href="javascript:goods('.$vo['id'].')" >详情</a>';
                    $selectResult[$key]['callback'] = '<a href="'.$vo['callback'].'&out_trade_no='.$vo['oid'].'" >异常点击</a>';

                    foreach ($ordertype as $k => $v){
                        if($v['id'] == $vo['type']) $selectResult[$key]['type'] = $v['name'];
                    }

            	}  

            	$return['total'] = $orders->where($where1)->where($where)->count();
                $return['sum'] = $orders->where($where1)->where($where)->sum('total_money');
            	$return['rows'] = $selectResult;
            	return json($return);
            }
        }
        $this->assign('type',$ordertype);
        return $this->fetch();
    }

    public function ordersAdd()
    {
        if(request()->isPost()){

            $param = input('param.');  
            $param = parseParams($param['data']);
            
            $ordersCon['goodsid']=$param['goodsid'];
            $ordersCon['orderscreatetime']=date('Y-m-d H:i:s');
            $ordersCon['orderscreatepersonid']=session("id");
            $ordersCon['ordersstatus']=0;//未结算
            $ordersCon['ordersquantity']=$param['ordersquantity'];


            //验证商品ID是否存在
            $goods = new goodsModel();
            $judgegoods = $goods->getOneGoods($param['goodsid']);  
            
            if(count($judgegoods)<=0){
            	return json(['code' => '1', 'data' => '', 'msg' => '']);
            }    
            
            $orders = new ordersModel();
            $flag = $orders->insertorders($ordersCon);

            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }

    // public function ordersEdit()
    // {
    // 	$orders = new ordersModel();

    //     if(request()->isPost()){

    //         $param = input('post.');
    //         $param = parseParams($param['data']);           
            
    //         $param['ordersmodpersonid'] = session("id");
    //         $param['ordersmodtime'] = date('Y-m-d H:i:s');
            
    //         $flag = $orders->editorders($param);

    //         return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    //     }

    //     $ordersid = input('param.ordersid'); 
    //     $oneorders= $orders->getOneorders($ordersid);      
        
    //     $this->assign(['ordersid' => $oneorders['ordersid'],'goodsid' => $oneorders['goodsid'],'ordersstatus' => $oneorders['ordersstatus'],'ordersquantity' => $oneorders['ordersquantity'],
    //         'forthwithgoodsprice' => $oneorders['forthwithgoodsprice'],
    //         'orderscreatepersonid' => $oneorders['orderscreatepersonid'],
    //     ]);
    //     return $this->fetch();
    // }

    public function ordersDel()
    {
        $ordersid = input('param.ordersid');

        $role = new ordersModel();
        $flag = $role->delorders($ordersid);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function ordersEdit(){
        $ordersid = input('param.id');
        $model = new ordersModel();
        $flag = $model->save(['state'=>3],['id'=>$ordersid]);
        $this->log->addLog($this->logData,'进行了订单发货操作');
        return json(['code' => $flag, 'data' => '', 'msg' => '']);        
    }

    public function getGoods($id){
        $model = new goodsModel();
        $list = $model->where(['oid'=>$id])->select();
        if($list){
            return json(['code' => 0, 'data' => $list, 'msg' => '成功']);
        }else{
            return json(['code' => 1, 'data' => '', 'msg' => '暂无数据']);
        }
    }

    //订单导出
    public function out(){

        if(request()->post()){
            $param = input('param.');
            $where = '';

            if(isset($param['type']) && !empty($param['type'])){
                $where['type'] = $param['type'];
            }

            if(isset($param['starttime']) && !empty($param['starttime']) && isset($param['endtime']) && !empty($param['endtime'])){
                $starttime = strtotime($param['starttime']);
                $endtime = strtotime($param['endtime']);
                $where['uptime'] = ['between',[$starttime,$endtime]];
            }

            $mode = new ordersModel();
            $selectResult = $mode
                ->field('oid,title,uptime,state,name,phone,address,total_money,type')
                ->where($where)->limit(0, 5000)->order('id desc')->select();
           // $state = array(-1=>'已退款','无效','待付款','已付款','已发货','已收货');
            $type = Db::name('tporderType')->column('id,name');

            if(count($selectResult) > 0){

                foreach($selectResult as $key=>$vo){
                    $selectResult[$key] = $vo->toArray();
                    $selectResult[$key]['type'] = $type[$vo['type']];
                   // $selectResult[$key]['state'] = $state[$vo['state']];
                }

                $fileheader = array('订单号','商品名称','发布时间','状态','姓名','电话','地址','总金额','订单类型');
                $this->exportExcel($selectResult,'订单表'.date('YmdHis',time()),$fileheader);
                exit();
            }

            $this->error('没有数据','orders/out');
        }

        $ordertype = Db::name('tporderType')->select();
        $this->assign('type',$ordertype);

        return $this->fetch();
    }

}
