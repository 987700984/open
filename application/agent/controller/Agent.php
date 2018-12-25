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
namespace app\agent\controller;

use app\admin\controller\Base;
use think\Db;
use app\agent\model\agentModel;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\agent\model\TpordersModel;
use app\settings\model\syncusersModel;

class Agent extends Base
{
    public function index()
    {

        if(request()->isAjax()){   	
        	$param = input('param.');
        	$limit = $param['pageSize'];
        	$offset = ($param['pageNumber'] - 1) * $limit;

        	$where = [];
        	if (isset($param['searchText']) && !empty($param['searchText'])) {
        		$where['username'] = $param['searchText'];
        	}

        	$selectResult=Db::name('tpagent')->where($where)->limit($offset,$limit)->select();

        	if(count($selectResult)>0){
        		
        		foreach($selectResult as $key=>$vo){
                    $level = unserialize($vo['level']);
                    $selectResult[$key]['sorename'] = '';
                    if (is_array($level)) {
                        foreach ($level as $k => $v) {
                            $name = Db::name('tpsoretype')->where(['id'=>$v])->value('name');
                            $selectResult[$key]['sorename'] .= $name.'&nbsp;&nbsp;';
                        }
                    }

        			$operate = [
                            '编辑' => url('agent/agentEdit', ['id' => $vo['id']]),
                            '删除' => "javascript:agentDel('".$vo['id']."')"
        			];
        			$selectResult[$key]['pic'] = '<img src="'.$vo['pic'].'" style="width:40px;" alt="">';
                    $selectResult[$key]['operate'] = showOperate($operate);

        		}
        		
        		$return['total'] = count($selectResult);
        		$return['rows'] = $selectResult;
        		return json($return);
        	}
        }
        return $this->fetch();
    }


    



    //添加代理
    public function agentadd(){ 

        $agent = new agentModel();
        if(request()->isPost()){
            $file = request()->file('file');
            $param = input('post.');
            $param = parseParams($param['data']);
            $param['password'] = md5($param['password']);
            $param['level'] = serialize($param['level']);

            $username = $param['username'];
            $hasUser = db('tpagent')->where('username', $username)->find();
            if ($hasUser) {
                return json(['code' => '-4', 'data' => '', 'msg' => '该用户名已存在']);
                
            }


        // var_dump($file);
    // 移动到框架应用根目录/public/uploads/ 目录下

            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    // echo $info->getExtension();
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getFilename(); 
                    $pic = $agent->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            // var_dump($_FILES);exit;
            $res = $agent->insertAgent($param);
            $this->log->addLog($this->logData,'进行了代理商添加操作');
            if ($res) {
                return json(['code' => '1', 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' => '-4', 'data' => '', 'msg' => '添加失败']);
            }
        }else{


            $soretype = $agent->soretype();
            $role = db('tprole')->select();
            $this->assign('role', $role);

            $this->assign('soretype', $soretype);
            return $this->fetch();
        }
    }

        public function agentEdit(){
            $id = input('param.id');
            $agent = new agentModel();
            if(request()->isAjax()){
                $file = request()->file('file');
                $param = input('post.');
                $param = parseParams($param['data']);

                if (isset($param['password'])) {
                    $param['password'] = md5($param['password']);
                }else{
                    unset($param['password']);
                }
                $param['level'] = serialize($param['level']);

                if (!$param['password']) {
                    unset($param['password']);
                }
                if($file){
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 jpg
                        // echo $info->getExtension();
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        // echo $info->getSaveName();
                        // 输出 42a79759f284b767dfcb2a0197904287.jpg
                        // echo $info->getFilename(); 
                        $sql = "select pic from ims_tpagent where id=".$param['id'];
                        $age = Db::query($sql);

                        $pic = $agent->moveOSS($info->getFilename(), $info->getSaveName());

                        $param['pic'] = $pic;
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                    }
                }
                $result = Db::table('ims_tpagent')->update($param);
                $this->log->addLog($this->logData,'进行了代理商编辑操作');
                if ($result) {
                    return json(['code' => '1', 'data' => '', 'msg' => '编辑成功']);
                    
                }else{
                    return json(['code' => '-4', 'data' => '', 'msg' => '编辑失败']);

                }
            }else{

                $sql = "select * from ims_tpagent where id=".$id;
                $res = Db::query($sql);
                foreach ($res as &$value) {
                    $value['level'] = unserialize($value['level']);
                    // $value['level'] = unserialize($value['level']);

                }
                // var_dump($res);exit;
                $soretype = $agent->soretype();

                $role = db('tprole')->select();
                $this->assign('role', $role);

                $this->assign('res', $res[0]);
                $this->assign('soretype', $soretype);
                $this->assign('id', $id);
                // var_dump($res);
                // echo 1;
                return $this->fetch();
            }

    }

    public function agentDel(){
        $id = input('param.id');
        $agent = new agentModel();
        $flag = $agent->agentDel($id);
        $this->log->addLog($this->logData,'进行了代理商删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


}
