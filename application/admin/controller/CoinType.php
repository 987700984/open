<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 18:01
 */
namespace app\admin\controller;
use app\admin\model\CoinUserAdmin;
use app\admin\model\CoinType as CoinModel;
use app\admin\model\Tpintegral;
use app\admin\model\Tpsoretype;
class CoinType extends Base
{

    /*发币类型列表*/
    public function index(){

            if(request()->isAjax()) {

                $param = input('param.');

                $limit = $param['pageSize'];
                $offset = ($param['pageNumber'] - 1) * $limit;
                $user = new CoinModel();
                $selectResult = $user->limit($offset, $limit)->order('id desc')->select();
                foreach ($selectResult as $key => $vo) {
                    $selectResult[$key]['operate'] = showOperate(['编辑' => url('coin_type/edit', ['id' => $vo['id']]),]);
                }
                $return['total'] = $user->where('1=1')->count();  //总数据
                $return['rows'] = $selectResult;

                return json($return);
            }

        return $this->fetch();
    }

    /*新增后台发币类型*/

    public function  add(){
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            $param['addtime'] =time();
            $model=new CoinModel();
            $flag =$model->save($param);
            if($flag){
                $this->log->addLog($this->logData,'进行了新增认购类型操作');
                return json(['code' =>1, 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' =>-1, 'data' => '', 'msg' => '添加失败']);
            }
        }
        return $this->fetch();
    }


    /*修改发币类型*/

    public function edit(){
        if(request()->isPost()){
            $param = input('post.');
            $param = parseParams($param['data']);
            $model= new CoinModel();
            $status=$model->update($param);
            if($status){
                $this->log->addLog($this->logData,'进行了修改认购类型操作');
                return ['code' =>1, 'data' => '', 'msg' => '修改成功'];
            }else{
                return ['code' =>-1, 'data' => '', 'msg' => '修改失败'];
            }
        }
        $id = input('param.id');
        $this->assign([
            'role' => CoinModel::get($id)
        ]);
        return $this->fetch();
    }


}