<?php
namespace app\shop\controller;

use app\admin\controller\Base;
use app\shop\model\Order;
use think\Db;


class Orders extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new Order();
    }

    //订单列表
    public function index()
    {


        if ($this->request->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where =$where1= [];
            if (!empty($param['searchText'])) {
                $where1['oid'] = ['like', '%' . $param['searchText'] . '%'];
                $where1['uid'] = ['like', '%' . $param['searchText'] . '%'];

            }
            if ( !empty($param['state']) || $param['state']=='0' ) {
                $where['state'] = ['eq', $param['state']];
            }
            if ( !empty($param['phone']) ) {
                $where['phone'] = ['eq', $param['phone']];
            }
            if ( !empty($param['pay'])) {
                $where['pay'] = ['eq', $param['pay']];
            }
            if($where1){
                $selectResult = $this->model->where($where)->where(function ($q) use ($where1) {
                    $q->whereOr($where1);
                })->limit($offset, $limit)->order('id desc')->select();
                $return['total'] = $this->model->where($where)->where(function ($q) use ($where1) {
                    $q->whereOr($where1);
                })->count();  //总数据
            }else{
                $selectResult = $this->model->where($where)->limit($offset, $limit)->order('id desc')->select();
                $return['total'] = $this->model->where($where)->count();  //总数据
            }

            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['uid'] = $vo->user->username;
                $selectResult[$key]['operate'] = '';

                $operate = [
                    '详情' => url('orders/detail', ['id' => $vo['id']]),

                    '删除' => "javascript:ordersDel('".$vo['id']."')",
                ];
                if($vo->getData('state')==2){
                    $operate['发货']= "javascript:edit1(".$vo['id'].",3)";
                }
                $selectResult[$key]['operate'] = showOperate($operate);

            }


            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    //发货
    public function edit(){
        if($this->request->isAjax()){
            $post=input('post.');
            if($this->model->update($post)){
                $oid=$this->model->where('id',$post['id'])->value('oid');
                $this->log->addLog($this->logData,'进行了订单号为【'.$oid.'】的发货操作');
                return ['code'=>1,'msg'=>'发货成功'];
            }else{
                return ['code'=>-1,'msg'=>'发货失败'];
            }
        }
    }

    //订单删除
    public function del()
    {
        if ($this->request->isAjax()) {
            $post = input('post.');
            if ($this->model->where('id', $post['id'])->delete()) {
                $this->model->orderinfo()->where('oid', $post['id'])->delete();
                $this->log->addLog($this->logData, '进行了订单的删除操作');
                return ['code' => 1, 'msg' => '发货成功'];
            } else {
                return ['code' => -1, 'msg' => '发货失败'];
            }
        }
    }


    //订单详情
    public function detail($id){
        $info=$this->model->where('id',$id)->find();
        $info_list=$this->model->orderinfo()->where('oid',$id)->select();
        $this->assign(['info'=>$info,'list'=>$info_list]);
        return $this->fetch();
    }

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


            $selectResult = Db::name('order')
                ->field('oid,title,uptime,state,name,phone,address,total_money,type,uid,courier,pay')
                ->where($where)->limit(0, 5000)->order('id desc')->select();
            $state = array(-1=>'已退款','无效','待付款','已付款','已发货','已收货');
            $type = Db::name('tporderType')->column('id,name');

            if(count($selectResult) > 0){

                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]['uptime'] = date('Y-m-d H:i:s', $vo['uptime']);
                    $selectResult[$key]['type'] = $type[$vo['type']];
                    $selectResult[$key]['state'] = $state[$vo['state']];
                }

                $fileheader = array('订单号','商品名称','发布时间','状态','姓名','电话','地址','总金额','订单类型','用户ID','快递编号','支付方式');
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