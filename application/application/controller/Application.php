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
namespace app\application\controller;

use app\admin\controller\Base;;

use app\application\model\applicationModel;

class Application extends Base
{
    //文章列表
    public function index()
    {
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            // var_dump($where);
            $application = new applicationModel();
            $selectResult = $application->getApplicationByWhere($where, $offset, $limit);
            $arr = array('禁用', '启用');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){
                    $res = $application->getApplicationcategory($vo['cid']);
            		$operate = [
            				'编辑' => url('application/applicationEdit', ['id' => $vo['id']]),
            				'删除' => "javascript:applicationDel('".$vo['id']."')"
            		];           	
                    $selectResult[$key]['operate'] = showOperate($operate);             
                    $selectResult[$key]['cid'] = $res[0]['name'];           
                    $selectResult[$key]['status'] = $arr[$vo['status']];            
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);            
                    $selectResult[$key]['endtime'] = date('Y-m-d H:i:s', $vo['endtime']);           
            		$selectResult[$key]['pic'] = '<img style="width:60px;" src="'.$vo['pic'].'">';          	
            	}            	
            	$return['total'] = count($selectResult);  //总数据
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    //添加文章
    public function applicationAdd()
    {   
        $application = new applicationModel();

        if(request()->isPost()){
            $file = request()->file('file');

            $param = input('param.');
            $param = parseParams($param['data']);
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
                    $pic = $application->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $param['addtime'] = time();
            $param['endtime'] = time();
            $flag = $application->insertApplication($param);
            $this->log->addLog($this->logData,'进行了应用添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $category = $application->category();

        $this->assign(['category' => $category]);
        return $this->fetch();
    }

    //编辑文章
    public function applicationEdit()
    {
    	$application = new applicationModel();

        if(request()->isPost()){
            $file = request()->file('file');

            $param = input('post.');
            $param = parseParams($param['data']);
            $param['endtime'] = time();
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
                    $pic = $application->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            
            $flag = $application->editApplication($param);
            $this->log->addLog($this->logData,'进行了应用编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $oneapplication= $application->getOneApplication($id);      
        $category = $application->category();
     
        $this->assign(['category' => $category]);
        $this->assign(['id' => $oneapplication['id'],'name' => $oneapplication['name'],'link' => $oneapplication['link'],'pic' => $oneapplication['pic'],'content' => $oneapplication['content'],'status' => $oneapplication['status'],'cid' => $oneapplication['cid']]);
        return $this->fetch();
    }

    //删除文章
    public function applicationDel()
    {
        $id = input('param.id');

        $role = new applicationModel();
        $flag = $role->delApplication($id);
        $this->log->addLog($this->logData,'进行了应用删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
