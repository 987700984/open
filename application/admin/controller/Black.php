<?php
namespace app\admin\controller;
use \think\Db;

class Black extends Base{

    /**
     * 列表
     */
    public function index(){
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['uid'] = $param['searchText'];
            }
//            $where1 = [];
//            if(session('soretype')){
//                $str = '';
//                $soretypes = session('soretype');
//                foreach ($soretypes as $value){
//                    $str .= $value.',';
//                }
//                $str = rtrim($str, ',');
//                $where1['id'] = ['in',$str];
//            }
            $selectResult = Db::name('blackList')->where($where)->order('status desc,id desc')->limit($offset,$limit)->select();

            if($selectResult){
                foreach($selectResult as $key=>$vo){

                    $selectResult[$key]['time'] = date('Y-m-d H:i:s', $vo['time']);

                    switch ($vo['status']) {
                        case '1':
                            $selectResult[$key]['status'] = '是';
                            break;
                        default:
                            $selectResult[$key]['status'] = '否';
                            break;
                    }

                    $operate = [
                        //'编辑' => url('black/edit', ['id' => $vo['id']]),
                        '不生效' => "javascript:edit('".$vo['id']."')"
                    ];

                    $selectResult[$key]['operate'] = showOperate($operate);

                }
            }


            $return['total'] = Db::name('blackList')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }


    /**
     * 编辑
     */
    public function edit(){
        $id = input('id');
//        $sid = Db::name('blackList')->where(['id'=>$id])->value('sid');
//        if(session('soretype')){
//            $soretypes = session('soretype');
//            if(!in_array($sid,$soretypes)){
//                return $this->error('权限不足');
//            }
//        }

        if(!$id){
            return json(['code'=>1]);
        }
        Db::name('blackList')->where(['id'=>$id])->update(['status'=>0]);
        $this->log->addLog($this->logData,'进行了糖果中心黑名单不生效操作');
        return json(['code'=>0]);
    }
}