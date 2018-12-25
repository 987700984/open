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
namespace app\integral\controller;

use app\admin\controller\Base;;

use app\admin\model\Tpintegral;
use app\integral\model\integralModel;
use think\Db;

class Integral extends Base
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
            $where1['id'] = array('in',$str);
        }

        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '';

            if($param['sid']){
                $where .=' and i.sid='.$param['sid'];
            }

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value){
                    $str .= $value.',';
                }
                $str = rtrim($str, ',');
                $where .= ' and i.sid in ('.$str.') ';
            }

            if (isset($param['searchText']) && !empty($param['searchText'])){
            	 $where .=' and (m.id ='.$param['searchText'].' or m.phone='.$param['searchText'].')';
            }

            if (session('level')) {
                $level = session('level');
                $in = implode(',', $level);
                $where .= ' and i.sid in('.$in.') ';
            }

            $integral = new integralModel();
            $selectResult = $integral->getintegralByWhere($where, $offset, $limit);
            
            $integralstatus=config('integralstatus');
            $orderstype=config('orderstype');
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){    
                    $operate = [
                            '加币' => url('integral/integralEdit', ['id' => $vo['id']]),
                            '减币' => url('integral/integral_reduce', ['id' => $vo['id']]),
                    ];              
                    $selectResult[$key]['operate'] = showOperate($operate);
                    $selectResult[$key]['addtime']=date('Y-m-d H:i:s', $vo['addtime']);

                }               
                // var_dump($vo);exit;
                $count = $integral->getAllintegral($where);
                $return['total'] = count($count); 
                $return['rows'] = $selectResult;
            	return json($return);
            }
        }
        $this->assign('soretype',$tpsoretype);

        return $this->fetch();
    }

    public function integraledit()
    {
        if(request()->isPost()){

            $param = input('post.');
            $param = parseParams($param['data']);

            $sid = Db::name('tpintegral')->where(['id'=>$param['id']])->value('sid');
            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($sid,$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $validate=[
                    'integral'  => '>:0|require|number',
            ];
            $message=array(
            'integral'  => '糖果数量必须大于0',
                    'integral.require'   => '糖果数量必须',
                    'integral.number'   => '糖果数量是数字'
                    );
            $result = $this->validate($param,$validate,$message);
            if(true !== $result){
                // 验证失败 输出错误信息
                return json(['code' => -1, 'data' => '', 'msg' =>$result]);

            }
            $integral = new Tpintegral();
            $flag = $integral->where('id',$param['id'])->find();
            if($flag){
                $param['amount']=intval($param['integral']);
                $param['sid']=$flag['sid'];
                $st=$flag->charge($param,$flag['uid'],'后台会员糖果加币');

                if($st){
                    $this->log->addLog($this->logData,'进行了后台会员糖果加币操作');
                    return json(['code' => 1, 'data' => '', 'msg' =>'增加糖果成功']);
                }else{
                    return json(['code' => -1, 'data' => '', 'msg' =>'增加糖果失败']);
                }

            }else{
                return json(['code' => -1, 'data' => '', 'msg' =>'此数剧不存在']);
            }
        }

        $integralid = input('param.id');
        $this->assign('id',$integralid);
        return $this->fetch();
    }


    public function integral_reduce()
    {

        if(request()->isPost()){
            $param = input('post.');
            $param = parseParams($param['data']);

            $sid = Db::name('tpintegral')->where(['id'=>$param['id']])->value('sid');
            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($sid,$soretypes)){
                    return $this->error('权限不足');
                }
            }


            $validate=[
                'integral'  => '>:0|require|number',
            ];
            $message=array(
                'integral'  => '糖果数量必须大于0',
                'integral.require'   => '糖果数量必须',
                'integral.number'   => '糖果数量是数字'
            );
            $result = $this->validate($param,$validate,$message);
            if(true !== $result){
                // 验证失败 输出错误信息
                return json(['code' => -1, 'data' => '', 'msg' =>$result]);

            }
            $integral = new Tpintegral();
            $flag = $integral->where('id',$param['id'])->find();

            if($flag){
                if($flag['integral']<intval($param['integral'])){
                    return json(['code' => -1, 'data' => '', 'msg' =>'币数量没那么多']);
                }
                $param['amount']=0-intval($param['integral']);
                $param['sid']=$flag['sid'];
                $st=$flag->charge($param,$flag['uid'],'后台会员糖果减币',1);
                if($st){
                    $this->log->addLog($this->logData,'进行了后台会员糖果减币操作');
                    return json(['code' => 1, 'data' => '', 'msg' =>'减少糖果成功']);
                }else{
                    return json(['code' => -1, 'data' => '', 'msg' =>'减少糖果失败']);
                }

            }else{
                return json(['code' => -1, 'data' => '', 'msg' =>'减少糖果失败']);
            }
        }
        $integralid = input('param.id');
        $this->assign('id',$integralid);
        return $this->fetch();
    }

//    public function soredel()
//    {
//        $id = input('param.id');
//
//        if(session('soretype')){
//            $soretypes = session('soretype');
//            if(!in_array($id,$soretypes)){
//                return $this->error('权限不足');
//            }
//        }
//
//        $role = new soretypeModel();
//        $flag = $role->delsore($id);
//        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//    }
}
