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
namespace app\exchange\controller;

use app\admin\controller\Base;;

use app\exchange\model\exchangeModel;
// use app\goods\model\goodsModel;
use think\Db;

class Exchange extends Base
{
    public function index()
    {
        $where1 = [];

        if (session('soretype')) {
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value) {
                $str .= $value . ',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in', $str];
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '';

            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  e.sid=' . $param['sid'];
                } else {
                    $where .= ' and e.sid in (' . $str . ')  ';
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  e.sid=' . $param['sid'];
                }
            }

            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where .= ' and (u.id=' . $param['searchText'] . ' or u.phone=' . $param['searchText']  . ')';
            }
            $exchange = new exchangeModel();
            $selectResult = $exchange->getexchangeByWhere($where, $offset, $limit);
            // var_dump($selectResult);exit;
            // $exchangestatus=config('exchangestatus');
            // $orderstype=config('orderstype');

            $arr = array(-1 => '未充值',0=>"取消",1 => '已充值',2=>'充值失败');
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){ 
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);  
                    if ($vo['stutas'] == -1) {
                        $operate = [
                            '人工充值' => "javascript:exchangeSave('".$vo['id']."')",
                            '不通过' => "javascript:exchangeDel('".$vo['id']."')",
                        ];
                    }else{
                        $operate = [    
                            // '编辑' => url('exchange/soreEdit', ['id' => $vo['id']]),

                        ];  
                    }
                    $selectResult[$key]['status'] = $arr[$vo['stutas']]; 
                          
                    $selectResult[$key]['operate'] = showOperate($operate);   
                    // $selectResult[$key]['status']=$exchangestatus[$vo['status']];
                    // $selectResult[$key]['exchangetype']=$exchangetype[$vo['exchangetype']];
                }               
                // var_dump($selectResult);exit;
                $return['total'] = $exchange->getAllsoretype($where);
                $return['rows'] = $selectResult;
            	return json($return);
            }
        }
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    /**
     * 更新兌换记录信息
     * @param $id
     */
    public function exchangeSave()
    {
        $id = input('param.id');
        $sid = Db::name('tpexchange')->where(['id'=>$id])->value('sid');

        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

        $role = new exchangeModel();
        $flag = $role->saveexchange($id);
        $this->log->addLog($this->logData,'进行了人工兑换操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    
    /**
     * 删除兌换记录信息
     * @param $id
     */
    public function exchangeDel()
    {
        $id = input('param.id');
        $sid = Db::name('tpexchange')->where(['id'=>$id])->value('sid');

        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

        $role = new exchangeModel();
        $flag = $role->delexchange($id);

        $this->log->addLog($this->logData,'进行了兑换取消操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
