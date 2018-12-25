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
namespace app\market\controller;
use app\admin\controller\Base;;
use app\market\model\MarketConfig;


use think\Db;

class Market extends Base
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

            $limit = input('pageSize',10);
            $offset = (input('pageNumber',1) - 1) * $limit;

            $where = '1=1 ';
$param=input('param.');
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid']) && in_array($param['sid'],$soretypes)) {
                    $where .= ' and  sid=' . $param['sid'];
                } else {
                    $where .= ' and sid in (' . $str . ')  ';
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  sid=' . $param['sid'];
                }
            }

            $list = MarketConfig::where($where)->limit($offset,$limit)->select();
            if($list){
                foreach ($list as $key => $value) {
                    $list[$key] = $value->toArray();
                    $list[$key]['operate'] = showOperate(['查看数据'=>url('market/line',['id'=>$value['id']]),'编辑' => url('market/edit',['id'=>$value['id']]),'删除' => "javascript:del('".$value['id']."')"]);
                }

                $return['total'] = MarketConfig::where($where)->count();
                $return['rows'] = $list;                 
            }
 
            return json($return);
        }
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    /**
     * 添加
     * @param $id
     */
    public function add()
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

        if(request()->isPost()){
            $param = input('post.');
            $param = parseParams($param['data']);

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $param['coin'] = strtolower($param['coin']);
            $param['change'] = $param['change_min'].','.$param['change_max'];
            unset($param['change_min']);
            unset($param['change_max']);
            $res = MarketConfig::create($param);
            if($res){
                $this->log->addLog($this->logData,'进行了行情添加操作');
                return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' => 0, 'data' => '', 'msg' => '添加失败']);
            }
        }else{
            $this->assign('soretype',$tpsoretype);
            return $this->fetch();            
        }
    }

    /**
     * 修改
     * @param $id
     */
    public function edit()
    {
        if(request()->isPost()){
            $param = input('post.');
            // var_dump($param);exit;
            $param = parseParams($param['data']);

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $id = $param['id'];
            $param['coin'] = strtolower($param['coin']);
            $param['change'] = $param['change_min'].','.$param['change_max'];
            unset($param['change_min']);
            unset($param['change_max']);
            $res = MarketConfig::where(['id'=>$id])->update($param);
            if($res){
                $this->log->addLog($this->logData,'进行了行情编辑操作');
                return json(['code' => 0, 'data' => '', 'msg' => '修改成功']);
            }else{
                return json(['code' => 1, 'data' => '', 'msg' => '修改失败']);
            }
        }else{
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

            $id = input('id');
            $sid = Db::name('market_config')->where(['id'=>$id])->value('sid');
            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($sid,$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $mc = MarketConfig::get($id);
            $mc = $mc->toArray();
            $change = explode(',',$mc['change']);
            $mc['change_min'] = $change[0];
            $mc['change_max'] = $change[1];
            $this->assign('res',$mc);
            $this->assign('soretype',$tpsoretype);
            return $this->fetch();            
        }
    }
    
    /**
     * 删除
     * @param $id
     */
    public function del()
    {
        $id = input('param.id');
        $sid = Db::name('market_config')->where(['id'=>$id])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }
        $res = MarketConfig::destroy($id);
        if($res){
            $this->log->addLog($this->logData,'进行了行情删除操作');
            $flag = ['code' =>0,'data' => '','msg' => '删除成功'];
        }else{
            $flag = ['code' =>1,'data' => '','msg' => '删除失败'];
        }
        return json($flag);
    }

    public function line(){
        $id = input('param.id');
        $mc = MarketConfig::get($id);
        $list = Db::name('market')->where(['coin'=>$mc['coin']])->order('create_time')->select();
        $time = '[';
        $price = '[';
        $change = '[';
        $max = 0;
        $min = 99999999999999;
        foreach ($list as $key => $value) {
            $time .= date('Ymd',$value['create_time']).','; 
            $price .= $value['price'].',';
            $change .= $value['change'].',';
            if($value['price']>$max){
                $max = $value['price'];
            }

            if($value['price']<$min){
                $min = $value['price'];
            }            
        }
        $time .= ']';
        $price .= ']';
        $change .= ']';
// echo $price;
        $this->assign('max',$max);
        $this->assign('min',$min);
        $this->assign('time',$time);
        $this->assign('price',$price);
        $this->assign('change',$change);        
// die;
        return $this->fetch();     
    }



}
