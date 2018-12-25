<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 14:59
 */
namespace app\admin\controller;

use app\admin\model\AgentLevel;
use app\admin\model\Tpsoretype;
use think\Controller;
use think\Db;


class Level extends Base
{
    //代理角色列表
    public function  index(){
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
            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }
            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['sid']= ['eq', $param['sid']];
                } else {
                    $where['sid']= ['in', $str];
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['sid']= ['eq', $param['sid']];
                }
            }
//            if (isset($param['sid']) && !empty($param['sid'])) {
//                $where['sid'] = ['eq', $param['sid']];
//            }
            $user = new AgentLevel();
            $selectResult = $user->where($where)->limit($offset, $limit)->order('sid desc, level desc')->select();;
            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['sid']=Tpsoretype::get($vo['sid'])['name'];
                if(1 == $vo['id']){
                    $selectResult[$key]['operate'] = '';
                    continue;
                }

                $operate = [
                    '编辑' => url('level/edit', ['id' => $vo['id']]),
//                    '删除' => "javascript:roleDel('".$vo['id']."')",
//                    '分配权限' => "javascript:giveQx('".$vo['id']."')"
                ];
                $selectResult[$key]['operate'] = showOperate($operate);

            }

            $return['total'] = $user->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign('coin',$tpsoretype);
        return $this->fetch();
    }

    //添加角色
    public function add()
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
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            if(empty($param['level'])){
                $param['level']=1;
            }

            $role = new AgentLevel();
            if($role->where(['level'=>$param['level'],'sid'=>$param['sid']])->count()>0){
                return json(['code' => -1, 'msg' => '该等级已存在']);
            }
            $flag = $role->insertRole($param);
           $this->log->addLog($this->logData,'进行了新增代理角色操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $this->assign('coin',$tpsoretype);

        return $this->fetch();
    }

    //编辑角色
    public function edit()
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
        if(request()->isPost()){
            $role = new AgentLevel();
            $param = input('post.');
            $param = parseParams($param['data']);
            if(empty($param['level'])){
                $param['level']=1;
            }

            $role = new AgentLevel();
            if($role->where(['level'=>$param['level'],'sid'=>$param['sid']])->count()>=2){
                return json(['code' => -1, 'msg' => '该等级已存在']);
            }
            $flag = $role->editRole($param);
           $this->log->addLog($this->logData,'进行了编辑代理角色操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $this->assign('coin',$tpsoretype);
        $this->assign([
            'role' => AgentLevel::get($id)
        ]);
        return $this->fetch();
    }

}