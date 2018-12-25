<?php
namespace app\admin\controller;
use \think\Db;

class Message extends Base{
    public function index(){
        $type = ['全部','wetoken','糖果中心'];

        if(request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (isset($param['type']) && !empty($param['type'])) {
                $where['type'] = $param['type'];
            }

            $selectResult = Db::name('message')->where($where)->limit($offset, $limit)->order('id desc')->select();
            $status = [1=>'待处理',2=>'已处理'];
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['type'] = $type[$selectResult[$key]['type']];
                $selectResult[$key]['status'] = $status[$selectResult[$key]['status']];
                $selectResult[$key]['addtime'] = date('Y-m-d H:i:s',$vo['addtime']);

                if($vo['status'] == 1){
                    $operate = [
                        '已处理' => "javascript:set('".$vo['id']."')",
                    ];
                    $selectResult[$key]['operate'] = showOperate($operate);
                }


            }
            $return['total'] = Db::name('message')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('type',$type);
        return $this->fetch();
    }

    public function set(){
        $id = input('id/d');

        $s = Db::name('message')->where(['id'=>$id])->update(['status'=>2]);

        if($s){
            return ['code' => 1, 'msg' => '修改成功'];
        }else{
            return ['code' => 0,  'msg' => '修改失败'];
        }
    }

    public function edit(){
        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);
        if(request()->isAjax()) {
            $tip = input('tip');

            $arr['message'] = ['tip'=>$tip];
            file_put_contents(__DIR__.'/../../config.json',json_encode($arr));
            $this->log->addLog($this->logData,'进行了编辑糖果中心客服操作');
            return json(['code'=>1,'msg'=>'修改成功']);
        }
//        $arr['message'] = '不要找我<a></a>';
//        dump($arr);
//        file_put_contents(__DIR__.'/../../config.json',json_encode($arr));
        $this->assign('cfg',$arr['message']);
        return $this->fetch();
    }
}