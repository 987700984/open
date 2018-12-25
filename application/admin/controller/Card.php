<?php
namespace app\admin\controller;
use think\Db;
class Card extends Base
{

    public function index()
    {
        if(request()->isAjax()){
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['uid'] = $param['searchText'];
            }

            $selectResult = Db::name('tpuserInfo')->where($where)->limit($offset,$limit)->order('status')->select();

            foreach($selectResult as $key=>$vo){
                switch ($vo['status']){
                    case 1:
                        $selectResult[$key]['status'] = '待审核';
                        break;
                    case 2:
                        $selectResult[$key]['status'] = '已通过';
                        break;
                    case 3:
                        $selectResult[$key]['status'] = '不通过';
                        break;
                    default:
                        break;
                }

                $operate = [
                    '通过' => "javascript:edit(".$vo['uid'].",2)",
                    '不通过' => "javascript:edit1(".$vo['uid'].",3)",
//                    '通过' => url('card/edit', ['uid' => $vo['uid'],'status'=>2]),
//                    '不通过' => url('card/edit', ['uid' => $vo['uid'],'status'=>3]),
                ];

                $selectResult[$key]['operate'] = showOperate($operate);
                if($selectResult[$key]['pic']){
                    $selectResult[$key]['pic'] = '<img class="container-item" src="'.$selectResult[$key]['pic'].'" bigUrl="'.$selectResult[$key]['pic'].'" />';
                }
                if($selectResult[$key]['pic1']){
                    $selectResult[$key]['pic1'] = '<img class="container-item" src="'.$selectResult[$key]['pic1'].'" bigUrl="'.$selectResult[$key]['pic1'].'" />';
//                    $selectResult[$key]['pic1'] = '<a href="'.$selectResult[$key]['pic1'].'" target= _blank><i class="fa fa-file-photo-o"></i></a>';
                }
                if($selectResult[$key]['pic2']){
                    $selectResult[$key]['pic2'] = '<img class="container-item" src="'.$selectResult[$key]['pic2'].'" bigUrl="'.$selectResult[$key]['pic2'].'" />';
//                    $selectResult[$key]['pic2'] = '<a href="'.$selectResult[$key]['pic2'].'" target= _blank><i class="fa fa-file-photo-o"></i></a>';
                }

            }

            $return['total'] = Db::name('tpuserInfo')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    public function edit()
    {
        $data = input('post.');
        Db::name('tpuserInfo')->update($data);
        $content=$data['status']==2?'进行了实名认证审核通过操作':'进行了实名认证审核不通过操作';
       $this->log->addLog($this->logData,$content);
        return json(['code' =>1,'msg' =>'修改成功']);
    }

}