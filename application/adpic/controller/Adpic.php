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
namespace app\adpic\controller;

use app\admin\controller\Base;;

use app\adpic\model\adpicModel;

class Adpic extends Base
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
            	$where['articlename'] = ['like', '%' . $param['searchText'] . '%'];
            }

            
            $adpic = new adpicModel();
            $selectResult = $adpic->getAdpicByWhere($where, $offset, $limit);
            $arr = array('禁用', '启用');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){
            		$operate = [
            				'编辑' => url('adpic/adpicEdit', ['id' => $vo['id']]),
            				'删除' => "javascript:adpicDel('".$vo['id']."')"
            		];           	
                    $selectResult[$key]['operate'] = showOperate($operate);             
                    $selectResult[$key]['status'] = $arr[$vo['status']];            
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);            
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
    public function adpicAdd()
    {   
        $adpic = new adpicModel();

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
                    $pic = $adpic->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $param['addtime'] = time();
            $flag = $adpic->insertAdpic($param);
            $this->log->addLog($this->logData,'进行了广告图添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }

    //编辑文章
    public function adpicEdit()
    {
    	$adpic = new adpicModel();

        if(request()->isPost()){
            $file = request()->file('file');

            $param = input('post.');
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
                    $pic = $adpic->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            
            $flag = $adpic->editAdpic($param);
            $this->log->addLog($this->logData,'进行了广告图编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $oneadpic= $adpic->getOneAdpic($id);      
     
        $this->assign(['id' => $oneadpic['id'],'link' => $oneadpic['link'],'pic' => $oneadpic['pic'],'status' => $oneadpic['status'], 'sort' => $oneadpic['sort']]);
        return $this->fetch();
    }

    //删除文章
    public function adpicDel()
    {
        $id = input('param.id');

        $role = new adpicModel();
        $flag = $role->delAdpic($id);
        $this->log->addLog($this->logData,'进行了广告图删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
