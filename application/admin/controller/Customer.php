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
namespace app\admin\controller;

use app\soretype\model\soretypeModel;
use think\Db;

class Customer extends Base
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

            $soretypestatus=['关闭','启用'];
            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $operate = [
                        '编辑' => url('customer/edit', ['id' => $vo['id']]),
//                        '删除' => "javascript:soreDel('".$vo['id']."')"
                    ];
                    $selectResult[$key]['operate'] = showOperate($operate);
                    $selectResult[$key]['status']=$soretypestatus[$vo['status']];

                    $selectResult[$key]['time']=date('Y-m-d H:i:s', $vo['time']);
                    $selectResult[$key]['all_score']=Db::name('tpintegral')->where(['sid'=>$vo['id']])->sum('integral');
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


    public function edit()
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


            if($param['give']>1){
                return json(['code' => 0,'msg' =>'转账手续费比例不能大于1']);
            }
            $data['id'] = $param['id'];
            $data['name'] = $param['name'];
            $data['lv1'] = $param['lv1'];
            $data['lv2'] = $param['lv2'];
            $data['lv3'] = $param['lv3'];
            $data['give'] = $param['give'];
            $data['max_give'] = $param['max_give'];
            $data['min_give'] = $param['min_give'];
            $data['num_give'] = $param['num_give'];
            $data['num'] = $param['num'];
            $data['time'] = $param['time'];
            $data['status'] = $param['status'];


            $flag = $soretype->editsoretype($data);
            $this->log->addLog($this->logData,'进行了代理商币种编辑操作');
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

        $this->assign(['id' => $onesoretype['id'], 'lv1' => $onesoretype['lv1'], 'lv2' => $onesoretype['lv2'], 'lv3' => $onesoretype['lv3'], 'exchange' => $onesoretype['exchange'], 'num' => $onesoretype['num'], 'proc' => $onesoretype['proc'], 'status' => $onesoretype['status'], 'time' => date('Y-m-d H:i:s', $onesoretype['time']), 'name' => $onesoretype['name'], 'max_proc' => $onesoretype['max_proc'], 'min_proc' => $onesoretype['min_proc'], 'min_num' => $onesoretype['min_num'], 'give' => $onesoretype['give'], 'max_give' => $onesoretype['max_give'], 'min_give' => $onesoretype['min_give'],'num_give' => $onesoretype['num_give'],]);
        return $this->fetch();
    }

}
