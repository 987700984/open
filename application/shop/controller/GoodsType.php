<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 14:59
 */

namespace app\shop\controller;

use app\admin\controller\Base;
use app\shop\model\TpgoodsCategory;
use app\shop\model\TpgoodsType;

class GoodsType extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new TpgoodsType();
    }
    //商品模型列表
    public function index()
    {


        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (!empty($param['searchText'])) {
                $where['goodsname'] = ['like', '%' . $param['searchText'] . '%'];

            }

            if ( !empty($param['cid'])) {
                $where['cid'] = ['eq', $param['cid']];
            }

            $selectResult = $this->model->where($where)->limit($offset, $limit)->order('tid desc')->select();
            $return['total'] = $this->model->where($where)->count();  //总数据
             foreach ($selectResult as $key => $vo) {

                $selectResult[$key]['operate'] = '';
                $selectResult[$key]['cid'] = $vo->cate->title;
                $operate = [
                    '编辑' => url('goodsType/edit', ['id' => $vo['tid']]),
                    '删除' => "javascript:roleDel('".$vo['tid']."')",
                ];
                $selectResult[$key]['operate'] = showOperate($operate);

            }


            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('cate', TpgoodsCategory::where('1=1')->select());
        return $this->fetch();
    }

    //添加总商品模型
    public function add()
    {

        if (request()->isPost()) {

            $param = input('post.');
            $param = parseParams($param['data']);
            if (empty($param['goodsname'])) {
                return json(['code' => -1, 'msg' => '模型名称不能为空']);
            }
            if (empty($param['cid'])) {
                return json(['code' => -1, 'msg' => '商品分类不能为空']);
            }
            if($this->model->save($param)){
                $this->log->addLog($this->logData,'进行了添加商品模型操作');
                return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' => -1, 'msg' => '添加失败']);
            }

        }
        $this->assign('cate', TpgoodsCategory::where('1=1')->select());
        return $this->fetch();
    }

    //编辑会员商品模型
    public function edit($id)
    {
        if (request()->isPost()) {

            $param = input('post.');
            $param = parseParams($param['data']);

            if (empty($param['goodsname'])) {
                return json(['code' => -1, 'msg' => '模型名称不能为空']);
            }
            if (empty($param['cid'])) {
                return json(['code' => -1, 'msg' => '商品分类不能为空']);
            }
            if($this->model->update($param)){
                $this->log->addLog($this->logData,'进行了编辑商品模型操作');
                return json(['code' => 1, 'data' => '', 'msg' => '编辑成功']);
            }else{
                return json(['code' => -1, 'msg' => '编辑失败']);
            }

        }
        $this->assign('info',$this->model->where('tid',$id)->find());
        $this->assign('cate', TpgoodsCategory::where('1=1')->select());
        return $this->fetch();
    }
    //删除会员商品模型
    public function del(){
        if (request()->isPost()) {
            $id=input('post.id');
            if($this->model->spec()->where('tid',$id)->find()){
                return json(['code' => -1, 'msg' => '该模型下有属性分类，请先删除']);
            }
            if($this->model->where('tid',$id)->delete()){
                return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
            }else{
                return json(['code' => -1, 'msg' => '删除失败']);
            }
        }
    }



}