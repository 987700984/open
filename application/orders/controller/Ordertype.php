<?php
namespace app\orders\controller;
use app\admin\controller\Base;
use think\Db;

class Ordertype extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = 1;
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where = 'name like "%'.$param['searchText'].'%"';
            }

            $orders = Db::name('tporderType');
            $selectResult = $orders->where($where)->limit($offset,$limit)->select();

            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $operate = [
                        '编辑' => url('ordertype/edit', ['id' => $vo['id']]),
                        '删除' => "javascript:del('".$vo['id']."')"
                    ];
                    $selectResult[$key]['operate'] = showOperate($operate);

                }

                $return['total'] = $orders->where($where)->count();
                $return['rows'] = $selectResult;
                return json($return);
            }
        }
        return $this->fetch();
    }
    public function add(){
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);

            if(count($param)<=0){
                return json(['code' => '1', 'data' => '', 'msg' => '']);
            }

            $model = Db::name('tporderType');
            $flag = $model->insert($param);

            if($flag){
                $this->log->addLog($this->logData,'进行了订单类型添加操作');
                return json(['code' => '0', 'data' => '', 'msg' => '']);
            }else{
                return json(['code' => '1', 'data' => '', 'msg' => '']);
            }
        }

        return $this->fetch();
    }

    public function edit(){
        $id = input('id');
        $model = Db::name('tporderType');
        $type = $model->where(['id'=>$id])->find();

        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            $flag = $model->where(['id'=>$param['id']])->update($param);

            if($flag){
                $this->log->addLog($this->logData,'进行了订单类型编辑操作');
                return json(['code' => '0', 'data' => '', 'msg' => '']);
            }else{
                return json(['code' => '1', 'data' => '', 'msg' => '']);
            }
            return $param;
        }
        $this->assign('result',$type);
        return $this->fetch();
    }

    public function del(){
        $id = input('param.id');
        $flag = Db::name('tporderType')->delete($id);

        if($flag){
            $this->log->addLog($this->logData,'进行了订单类型删除操作');
            return json(['code' => '0', 'data' => '', 'msg' => '']);
        }else{
            return json(['code' => '1', 'data' => '', 'msg' => '']);
        }
    }
    
}