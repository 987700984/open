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
namespace app\siteinfo\controller;

use app\admin\controller\Base;;

use app\siteinfo\model\siteinfoModel;
use think\Db;

class Siteinfo extends Base
{
    public function index(){
        if(request()->isAjax()){

            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = '1=1';

            $selectResult = Db::name('upApp')->where($where)->limit($offset,$limit)->order('id desc')->select();
            $type = array('安卓', '苹果');
            $upgrade = array('升级提醒','强制升级');

            if(count($selectResult) > 0){
                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]['time'] = date('Y-m-d H:i:s', $vo['time']);
                    $selectResult[$key]['type'] = $type[$vo['type']];
                    $selectResult[$key]['upgrade'] = $upgrade[$vo['upgrade']];
                    $operate = [
                        '修改' => url('siteinfo/edit', ['id' => $vo['id']]),
                        '删除' => "javascript:del('".$vo['id']."')"
                    ];

                    $selectResult[$key]['operate'] = showOperate($operate);
                }
                $res['total'] = Db::name('upApp')->where(1)->count();
                $res['rows'] = $selectResult;

            }
            return json($res);
        }
        return $this->fetch();
    }

    public function add()
    {
        if(request()->isPost()){
            $param = input('param.');
            $param = parseParams($param['data']);
            $param['time'] = time();
            Db::name('upApp')->insert($param);
            $type = array('安卓', '苹果');
            $this->log->addLog($this->logData,'进行了钱包（'.$type[$param['type']].'）升级操作');
            return json(['code' => 1,'msg' => '添加成功']);

        }

        return $this->fetch();
    }

    public function edit()
    {
        if(request()->isPost()){
            $param = input('param.');
            $param = parseParams($param['data']);
            $param['time'] = time();

            Db::name('upApp')->where(['id'=>$param['id']])->update($param);
            $type = array('安卓', '苹果');
            $this->log->addLog($this->logData,'进行了钱包（'.$type[$param['type']].'）修改操作');
            return json(['code' => 1,'msg' => '添加成功']);

        }
        $id = input('id/d');
        $res = Db::name('upApp')->where(['id'=>$id])->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function del()
    {
        $id = input('id/d');
        Db::name('upApp')->delete(['id'=>$id]);
        $this->log->addLog($this->logData,'进行了钱包记录修改操作');
        return json(['code' => 1,'msg' => '删除成功']);
    }

    public function wallet_android()
    {
        $siteinfo = new siteinfoModel();
        if(request()->isPost()){
            $param = input('param.');
            $param = parseParams($param['data']);
            $value['value'] = serialize($param);
            $flag = $siteinfo->siteinfoSave($value, 'wallet_android');
            $this->log->addLog($this->logData,'进行了钱包（Android）升级操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

        }

        $return = $siteinfo->getsiteinfo('wallet_android');
        if (isset($return)) {
            $this->assign(['version' => $return['version'], 'description' => $return['description'], 'upgrade' => $return['upgrade'], 'url' => $return['url'] , 'apk' => $return['apk']]);
        }    
        $this->assign(['title' => 'Android', 'wallet' => 'wallet_android']); 
        return $this->fetch('index');
    }

    public function wallet_iphone()
    {
        $siteinfo = new siteinfoModel();
        if(request()->isPost()){
            $param = input('param.');
            $param = parseParams($param['data']);
            $value['value'] = serialize($param);
            $flag = $siteinfo->siteinfoSave($value, 'wallet_iphone');
            $this->log->addLog($this->logData,'进行了钱包（iOS）升级操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

        }

        $return = $siteinfo->getsiteinfo('wallet_iphone');
        if (isset($return)) {
            $this->assign(['version' => $return['version'], 'description' => $return['description'], 'upgrade' => $return['upgrade'], 'url' => $return['url']]);
        }    
        $this->assign(['title' => 'iPhone', 'wallet' => 'wallet_iphone']); 
        return $this->fetch('index');
    }

    

}
