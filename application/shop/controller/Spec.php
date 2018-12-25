<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 14:59
 */

namespace app\shop\controller;

use app\admin\controller\Base;
use app\shop\model\Tpspec;


class Spec extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new Tpspec();
    }
    //规格属性分类列表
    public function index()
    {


        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (!empty($param['searchText'])) {
                $where['spec_name'] = ['like', '%' . $param['searchText'] . '%'];

            }

            if ( !empty($param['tid'])) {
                $where['tid'] = ['eq', $param['tid']];
            }

            $selectResult = $this->model->where($where)->limit($offset, $limit)->order('sid desc')->select();
            $return['total'] = $this->model->where($where)->count();  //总数据
             foreach ($selectResult as $key => $vo) {

                $selectResult[$key]['operate'] = '';
                $selectResult[$key]['tid'] =$vo->goodstype->cate->title.'-'. $vo->goodstype->goodsname;
                $operate = [
                    '编辑' => url('spec/edit', ['id' => $vo['sid']]),
                    '删除' => "javascript:roleDel('".$vo['sid']."')",
                ];
                $selectResult[$key]['operate'] = showOperate($operate);

            }


            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('cate', $this->model->goodstype()->where('1=1')->select());
        return $this->fetch();
    }

    //添加总规格属性分类
    public function add()
    {

        if (request()->isPost()) {

            $param = input('post.');
            $param = parseParams($param['data']);
            if (empty($param['spec_name'])) {
                return json(['code' => -1, 'msg' => '规格属性名称不能为空']);
            }
            if (empty($param['tid'])) {
                return json(['code' => -1, 'msg' => '模型不能为空']);
            }
            if($this->model->save($param)){
                $this->log->addLog($this->logData,'进行了添加规格属性分类操作');
                return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' => -1, 'msg' => '添加失败']);
            }

        }
        $this->assign('cate', $this->model->goodstype()->where('1=1')->select());
        return $this->fetch();
    }

    //编辑会员规格属性分类
    public function edit($id)
    {
        if (request()->isPost()) {

            $param = input('post.');
            $param = parseParams($param['data']);

            if (empty($param['spec_name'])) {
                return json(['code' => -1, 'msg' => '规格属性名称不能为空']);
            }
            if (empty($param['tid'])) {
                return json(['code' => -1, 'msg' => '模型不能为空']);
            }
            if($this->model->update($param)){
                $this->log->addLog($this->logData,'进行了编辑规格属性分类操作');
                return json(['code' => 1, 'data' => '', 'msg' => '编辑成功']);
            }else{
                return json(['code' => -1, 'msg' => '编辑失败']);
            }

        }
        $this->assign('info',$this->model->where('sid',$id)->find());
        $this->assign('cate', $this->model->goodstype()->where('1=1')->select());
        return $this->fetch();
    }
    //删除会员规格属性分类
    public function del(){
        if (request()->isPost()) {
            $id=input('post.id');
            if($this->model->spec_item()->where('sid',$id)->find()){
                return json(['code' => -1, 'msg' => '该分类下有规格属性值，请先删除']);
            }
            if($this->model->where('sid',$id)->delete()){
                return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
            }else{
                return json(['code' => -1, 'msg' => '删除失败']);
            }
        }
    }



}