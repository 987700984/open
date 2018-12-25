<?php
namespace app\admin\controller;

use think\Db;

class Help extends Base
{
    //帮助列表
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

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['a.sid'] = $param['searchText'];
            }

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value){
                    $str .= $value.',';
                }
                $str = rtrim($str, ',');
                $where['a.sid']= ['in',$str];
            }

            $selectResult = Db::name('tphelp')
                ->alias('a')
                ->field('a.*,b.name as level')
                ->join('ims_tpsoretype b','a.sid=b.id')
                ->where($where)
                ->limit($offset,$limit)
                ->select();

            foreach($selectResult as $key=>$vo){

                $operate = [
                    '编辑' => url('help/edit', ['id' => $vo['id']]),
                    '删除' => "javascript:del('".$vo['id']."')",
                ];
                $selectResult[$key]['operate'] = showOperate($operate);
                $selectResult[$key]['addtime'] = date('Y-m-d',$selectResult[$key]['addtime']);

            }

            $return['total'] = Db::name('tphelp')->alias('a')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //添加角色
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

            $param = input('param.');
            $param = parseParams($param['data']);

            $param['addtime'] = time();

            $flag = Db::name('tphelp')->insert($param);

            if($flag){
               $this->log->addLog($this->logData,'进行了新增糖果中心帮助操作');
                return json(['code' => 1, 'msg' =>'增加成功']);
            }else{
                return json(['code' => 0, 'msg' =>'增加失败']);
            }
        }

        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //编辑角色
    public function edit()
    {
        if(request()->isPost()){
            $param = input('post.');
            $param['content'] = html_entity_decode($param['content']);
            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $where['id'] = $param['id'];
            Db::name('tphelp')->where($where)->update($param);
           $this->log->addLog($this->logData,'进行了编辑糖果中心帮助操作');
            return json(['code' => 1, 'msg' => '修改成功']);
        }

        $id = input('id');
        $sid = Db::name('tphelp')->where(['id'=>$id])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

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

        $data = Db::name('tphelp')->where(['id'=>$id])->find();

        $this->assign('soretype',$tpsoretype);
        $this->assign('level',$data);
        return $this->fetch();
    }

    //删除角色
    public function del()
    {
        $id = input('param.id');
        $sid = Db::name('tphelp')->where(['id'=>$id])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

        Db::name('tphelp')->delete($id);
       $this->log->addLog($this->logData,'进行了删除糖果中心帮助操作');
        return json(['code' => 1]);
    }


}