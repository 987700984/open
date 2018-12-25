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
namespace app\soretype\controller;

use app\admin\controller\Base;;

use app\soretype\model\soretypeModel;
// use app\goods\model\goodsModel;
use think\Db;

class Soretype extends Base
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

            $where = '1=1 ';
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  id=' . $param['sid'];
                } else {
                    $where .= ' and id in (' . $str . ')  ';
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  id=' . $param['sid'];
                }
            }

            $soretype = new soretypeModel();
            $selectResult = $soretype->getsoretypeByWhere($where, $offset, $limit);
            
            $soretypestatus=config('soretypestatus');
            $orderstype=config('orderstype');
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){    
                    $operate = [
                            '编辑' => url('soretype/soreEdit', ['id' => $vo['id']]),
                            '删除' => "javascript:soreDel('".$vo['id']."')"
                    ];              
                    $selectResult[$key]['operate'] = showOperate($operate);   
                    $selectResult[$key]['status']=$soretypestatus[$vo['status']];

                    $selectResult[$key]['time']=date('Y-m-d H:i:s', $vo['time']);

                    // $selectResult[$key]['soretypetype']=$soretypetype[$vo['soretypetype']];
                }               
                // var_dump($vo);exit;
                $return['total'] = $soretype->getAllsoretype($where);
                $return['rows'] = $selectResult;
            	return json($return);
            }
        }
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    public function soreadd()
    {
        if(request()->isPost()){

            $param = input('param.');  
            $param = parseParams($param['data']);
            $param['time'] = strtotime($param['time']);

            if($param['num']>100){
                return json(['code' => 1,'msg' =>'每日限额不能超过100%']);
            }

            if($param['proc']>1){
                return json(['code' => 1,'msg' =>'c2c手续费比例不能大于1']);
            }

            if($param['give']>1){
                return json(['code' => 1,'msg' =>'转账手续费比例不能大于1']);
            }

            $sore = new soretypeModel();
            $flag = $sore->insertsore($param);
            $this->log->addLog($this->logData,'进行了币种添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }

    public function soreedit()
    {
    	$soretype = new soretypeModel();

        if(request()->isPost()){

            $param = input('post.');
            $param = parseParams($param['data']);

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['id'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $param['time'] = strtotime($param['time']);

            if($param['num']>100){
                return json(['code' => 0,'msg' =>'每日限额不能超过100%']);
            }

            if($param['proc']>1){
                return json(['code' => 0,'msg' =>'c2c手续费比例不能大于1']);
            }

            if($param['give']>1){
                return json(['code' => 0,'msg' =>'转账手续费比例不能大于1']);
            }
            $flag = $soretype->editsoretype($param);
            $this->log->addLog($this->logData,'进行了币种编辑操作');
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('id');

        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($id,$soretypes)){
                return $this->error('权限不足');
            }
        }


        $soretypeid = input('param.id'); 
        $onesoretype= $soretype->getOnesoretype($soretypeid);      
        
        // $this->assign(['id' => $oneorders['id'],'goodsid' => $oneorders['goodsid'],'ordersstatus' => $oneorders['ordersstatus'],'ordersquantity' => $oneorders['ordersquantity'],
        //     'forthwithgoodsprice' => $oneorders['forthwithgoodsprice'],
        //     'orderscreatepersonid' => $oneorders['orderscreatepersonid'],
        // ]);
        $this->assign(['id' => $onesoretype['id'], 'lv1' => $onesoretype['lv1'], 'lv2' => $onesoretype['lv2'], 'lv3' => $onesoretype['lv3'], 'exchange' => $onesoretype['exchange'], 'num' => $onesoretype['num'], 'proc' => $onesoretype['proc'], 'status' => $onesoretype['status'], 'time' => date('Y-m-d H:i:s', $onesoretype['time']), 'name' => $onesoretype['name'], 'max_proc' => $onesoretype['max_proc'], 'min_proc' => $onesoretype['min_proc'], 'min_num' => $onesoretype['min_num'], 'give' => $onesoretype['give'], 'max_give' => $onesoretype['max_give'], 'min_give' => $onesoretype['min_give'],'num_give' => $onesoretype['num_give'],'poundage'=>$onesoretype['poundage'],'min_poundage'=>$onesoretype['min_poundage'],'exchange_num'=>$onesoretype['exchange_num']]);
        return $this->fetch();
    }

    public function soredel()
    {
        $id = input('param.id');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($id,$soretypes)){
                return $this->error('权限不足');
            }
        }

        $role = new soretypeModel();
        $flag = $role->delsore($id);
        $this->log->addLog($this->logData,'进行了币种删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function config(){

        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);

        if(request()->isAjax()) {
            $param = input('status');
            $arr['sweet'] = ['status'=>$param];
            file_put_contents(__DIR__.'/../../config.json',json_encode($arr));
            $this->log->addLog($this->logData,'进行了糖果中心配置修改操作');
            return json(['code'=>1,'msg'=>'修改成功']);
        }

        $this->assign('cfg',$arr['sweet']);
        return $this->fetch();
    }
}
