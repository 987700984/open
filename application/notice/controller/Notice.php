<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2099 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\notice\controller;

use app\admin\controller\Base;
use think\Db;
use app\notice\model\noticeModel;

class Notice extends Base
{
    //公告列表
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
            $where=[];

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');

                if (isset($param['searchText']) && !empty($param['searchText']) && in_array($param['searchText'],$soretypes) ) {
                    $where['a.sid'] = $param['searchText'];
                }else{
                    foreach ($soretypes as $value){
                        $str .= $value.',';
                    }
                    $str = rtrim($str, ',');
                    $where['a.sid'] = ['in',$str];
                }

            }else{
                if (isset($param['searchText']) && !empty($param['searchText'])) {
                    $where['a.sid'] = $param['searchText'];
                }
            }

            $where['a.is_personal'] = ['eq',0];
            $selectResult =  Db::name('notice')
                ->alias('a')
                ->join('ims_tpsoretype b','a.sid=b.id')
                ->field('a.*,b.name')
                ->where($where)
                ->limit($offset, $limit)
                ->order('id desc')
                ->select();

            if(count($selectResult) > 0){     
                $status = config('user_status');            
                foreach($selectResult as $key=>$vo){    
                    $operate = [
                            '编辑' => url('notice/noticeEdit', ['id' => $vo['id']]),
                            '删除' => "javascript:noticeDel('".$vo['id']."')"
                    ];              
                    $selectResult[$key]['operate'] = showOperate($operate);
                    $selectResult[$key]['addtime'] = date('Y/m/d H:i:s',$vo['addtime']);
                    // dump($selectResult);exit;
            		// if( 1 == $vo['id'] ){
            		// 	$selectResult[$key]['operate'] = '';
            		// }
            	}           
                $count =  Db::table('ims_notice')->alias('a')->where($where)->select();
                $return['total'] = count($count);  //总数据
             	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //添加公告
    public function noticeadd()
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

        $notice = new noticeModel();

        if(request()->isPost()){

            $param = input('post.');
            $param['addtime'] = time();

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $flag = $notice->insertNotice($param);
            $this->log->addLog($this->logData,'进行了糖果公告添加操作');

            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //编辑公告
    public function noticeEdit()
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

        $notice = new noticeModel();

        if(request()->isPost()){

            $param = input('post.');

            // $param['noticemodperson'] = session("id");
            // $param['addtime'] = time();
            $flag = $notice->editNotice($param);
            $this->log->addLog($this->logData,'进行了糖果公告编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        
            
        }
        $noticeid = input('param.id');
        $sid = Db::name('notice')->where(['id'=>$noticeid])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }
        $onenotice= $notice->getOneNotice($noticeid);  
        // var_dump($onenotice);exit;
        $this->assign(['sid'=>$onenotice['sid'],'id' => $onenotice['id'], 'title' => $onenotice['title'],'content' => $onenotice['content'], 'pic' => $onenotice['pic'], 'recommend' => $onenotice['recommend']]);

        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //删除公告
    public function noticeDel()
    {
        $noticeid = input('param.noticeid');
        $sid = Db::name('notice')->where(['id'=>$noticeid])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }
        $role = new noticeModel();
        $flag = $role->delNotice($noticeid);
        $this->log->addLog($this->logData,'进行了糖果公告删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function config(){
        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);
        if(request()->isAjax()) {
            $time = intval(input('time'));
            $arr['notice'] = ['time'=>$time];
            file_put_contents(__DIR__.'/../../config.json',json_encode($arr));
            $this->log->addLog($this->logData,'进行了糖果公告配置修改操作');
            return json(['code'=>1,'msg'=>'修改成功']);
        }

        $this->assign('cfg',$arr['notice']);
        return $this->fetch();
    }
}
