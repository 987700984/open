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
namespace app\bill\controller;

use app\admin\controller\Base;;

use app\bill\model\billModel;
use think\Db;

class Bill extends Base
{
    public function index()
    {
        $where1 = [];

        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in',$str];
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();

        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '';

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value){
                    $str .= $value.',';
                }
                $str = rtrim($str, ',');
                $where .= ' and b.sid in ('.$str.') ';
            }

            if (isset($param['searchText']) && !empty($param['searchText'])) {
                if(isset($soretypes)){
                    if(in_array($param['searchText'],$soretypes)){
                        $where = ' and b.sid = '.$param['searchText'].' ';
                    }
                }else{
                    $where = ' and b.sid = '.$param['searchText'].' ';
                }

            }
            if(isset($param['phone']) && !empty($param['phone'])){
                $where .= 'and (u.phone = '.$param['phone'].' or u.id='.$param['phone'].')';
            }

            $bill = new billModel();
            $selectResult = $bill->getbillByWhere($where, $offset, $limit);
            $type = array('增加', '减少');
            $type2 = array('后台增加','注册', '转让', '兑换', '推荐','交易','佣金','定时发放','每日生息','矿机');

            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){  
                $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);  
                $selectResult[$key]['proc'] = $vo['proc'] / 10000;  
                    $operate = [
                            '删除' => "javascript:billDel('".$vo['id']."')"
                    ];            

                    $selectResult[$key]['operate'] = showOperate($operate);   
                    $selectResult[$key]['type'] = $type[$vo['type']];
                    $selectResult[$key]['type2'] = $type2[$vo['type2']];

                }
                $return['total'] = $bill->getAllbill($where);
                $return['rows'] = $selectResult;
            	return json($return);
            }
        }
        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    

    public function billDel()
    {
        $id = input('param.id');
        $sid = Db::name('tpbill')->where(['id'=>$id])->value('sid');

        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

        $role = new billModel();
        $flag = $role->delbill($id);
        $this->log->addLog($this->logData,'进行了糖果流水删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function out(){
        $where1 = [];
        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in',$str];
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();

        if(request()->post()){
            $param = input('param.');
            $where = '';

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value){
                    $str .= $value.',';
                }
                $str = rtrim($str, ',');
                $where .= ' and b.sid in ('.$str.') ';
            }

            if (isset($param['soretype']) && !empty($param['soretype'])) {
                if(isset($soretypes)){
                    if(in_array($param['soretype'],$soretypes)){
                        $where = ' and b.sid = '.$param['soretype'].'';
                    }
                }else{
                    $where = ' and b.sid = '.$param['soretype'].' ';
                }

            }
            if(isset($param['phone']) && !empty($param['phone'])){
                $where .= ' and (u.phone = '.$param['phone'].' or u.id='.$param['phone'].')';
            }
            if(isset($param['starttime']) && !empty($param['starttime'])){
                $starttime = strtotime($param['starttime']);
                $where .= ' and b.addtime > '.$starttime;
            }
            if(isset($param['endtime']) && !empty($param['endtime'])){
                $endtime = strtotime($param['endtime']);
                $where .= ' and b.addtime <'.$endtime.' ';
            }

            $bill = new billModel();
            $selectResult = $bill->getbillByWhere($where,0,5000);
            $type = array('增加', '减少');
            $type2 = array('后台增加','注册', '转让', '兑换', '推荐','交易','佣金','定时发放','每日生息','矿机');

            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);
                    $selectResult[$key]['proc'] = $vo['proc'] / 10000;
                    $selectResult[$key]['type'] = $type[$vo['type']];
                    $selectResult[$key]['type2'] = $type2[$vo['type2']];
                    unset($selectResult[$key]['sid']);
                }
                $fileheader = array('流水ID','订单号','手续费','类型','对方ID','种类','币种名称','用户ID','用户昵称','电话号码','备注','时间','数量','对方昵称');
                $this->exportExcel($selectResult,'流水表'.date('YmdHis',time()),$fileheader);
                exit();
            }

            $this->error('没有数据','bill/out');
        }

        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

}
