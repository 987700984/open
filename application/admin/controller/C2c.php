<?php

namespace app\admin\controller;

use app\admin\model\Tpsoretype;
use \think\Db;


class C2c extends Base
{
    public function index()
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
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '1=1 ';

            if (session('soretype')) {
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value) {
                    $str .= $value . ',';
                }
                $str = rtrim($str, ',');
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  sid=' . $param['sid'];
                } else {
                    $where .= ' and sid in (' . $str . ')  ';
                }

            }else{
                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where .= ' and  sid=' . $param['sid'];
                }
            }



            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where .= ' and (cid=' . $param['searchText'] . ' or uid=' . $param['searchText'] . ' or payid=' . $param['searchText'] . ')';
            }

            $selectResult = Db::name('tpc2c')->where($where)->order('id desc,status')->limit($offset, $limit)->select();

            foreach ($selectResult as $key => $vo) {

                $selectResult[$key]['create_time'] = date('Y-m-d H:i:s', $vo['create_time']);
                $selectResult[$key]['update_time'] = date('Y-m-d H:i:s', $vo['update_time']);
                $selectResult[$key]['sid'] =Db::name('tpsoretype')->where(['id'=>$vo['sid']])->value('name');
                switch ($vo['status']) {
                    case '0':
                        $selectResult[$key]['status'] = '订单结束';
                        break;
                    case '1':
                        $selectResult[$key]['status'] = '待接单';
                        break;
                    case '5':
                        $selectResult[$key]['status'] = '已完成';
                        break;
                    case '6':
                        $selectResult[$key]['status'] = '已作废';
                        break;
                    default:
                        $selectResult[$key]['status'] = '交易中';
                        break;
                }
                $selectResult[$key]['type'] = $vo['type'] == 1 ? '出售' : '求购';
                $selectResult[$key]['cid'] .= '';

                $operate = [
                    '编辑' => "javascript:edit('" . $vo['id'] . "')",
                    '删除' => "javascript:del('" . $vo['id'] . "')"
                ];

                $selectResult[$key]['operate'] = showOperate($operate);

            }
            $return['total'] = Db::name('tpc2c')->where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign('level', $tpsoretype);
        return $this->fetch();
    }

    //编辑订单
    public function edit(){
        $id = input('id/d');
        $status = input('status/d');

        $c2c = Db::name('tpc2c')->where(['id' => $id])->find();

        if(!$c2c){
            return json(['code' => 0 , 'msg' => '订单不存在']);
        }

        //买家未付款却点已付款
        if($c2c['status'] == 3){
            Db::name('tpc2c')->where(['id'=>$c2c['id']])->update(['status'=>0]);
            //积分解冻
            Db::name('tpintegral')->where(['uid'=>$c2c['uid'],'sid'=>$c2c['sid']])->update(['frozen'=>['exp','frozen-'.($c2c['num']+$c2c['fee'])],'integral'=>['exp','integral+'.($c2c['num']+$c2c['fee'])]]);
            Db::name('tpc2cBill')->insert(['cid'=>$c2c['id'],'uid'=>0,'time'=>time(),'content'=>'客服结束订单']);
            Db::name('blackList')->insert(['uid'=>$c2c['payid'],'oid'=>$c2c['id'],'time'=>time()]);

            $this->log->addLog($this->logData,'修改了订单'.$c2c['cid']);
            return json(['code' => 1 , 'msg' => '修改成功']);
        }else{
            return json(['code' => 0 , 'msg' => '订单交易中不可修改']);
        }

    }

    public function del()
    {
        $id = input('id');

        $sid = Db::name('tpc2c')->where(['id' => $id])->value('sid');

        if (session('soretype')) {
            $soretypes = session('soretype');
            if (!in_array($sid, $soretypes)) {
                return $this->error('权限不足');
            }
        }

        if (!$id) {
            return json(['code' => 1]);
        }
        Db::name('tpc2c')->delete($id);
       $this->log->addLog($this->logData,'进行了c2c删除操作');
        return json(['code' => 0]);
    }

}