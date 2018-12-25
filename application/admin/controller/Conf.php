<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 14:59
 */
namespace app\admin\controller;


use think\Db;

class Conf extends Base
{

    public function index()
    {

        if(request()->isAjax()){

            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where =  [];

            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
                $where['uid'] = ['eq', $param['searchText']];
            }
            $selectResult = $this->log->whereOr($where)->limit($offset, $limit)->order('id desc')->select();
            if(count($selectResult) > 0){
//                foreach($selectResult as $key=>$vo){
//                    $operate = [
//                        '编辑' => url('ordertype/edit', ['id' => $vo['id']]),
//                        '删除' => "javascript:del('".$vo['id']."')"
//                    ];
//                    $selectResult[$key]['operate'] = showOperate($operate);
//
//                }

                $return['total'] =  $this->log->whereOr($where)->count();
                $return['rows'] = $selectResult;
                return json($return);
            }
            return json($selectResult);

        }
        return $this->fetch();
    }

    public function rtid(){
        if(request()->isAjax()){
            $param = input('post.');
            $param = parseParams($param['data']);
            $res = Db::name('config')->where(['id'=>3])->update(['content'=>$param['rtid']]);
            if($res){
                $this->log->addLog($this->logData,'修改了默认推荐人');
                return json(['code'=>1,'msg'=>'修改成功']);
            }else{
                return json(['code'=>0,'msg'=>'修改失败']);
            }
        }else{
            $rtid = Db::name('config')->where(['id'=>3])->value('content');
            $this->assign('rtid',$rtid);
            return $this->fetch();
        }

    }



}