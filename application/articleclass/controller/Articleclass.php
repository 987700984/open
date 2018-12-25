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
namespace app\articleclass\controller;

use app\admin\controller\Base;

use app\articleclass\model\articleclassModel;

class Articleclass extends Base
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
            	$where['title'] = ['like', '%' . $param['searchText'] . '%'];
            }
            // $where['status'] = 1;
            
            $article = new articleclassModel();
            $result = $article->getArticleclassByWhere($where);
            //无限级
            $selectResult = $article->getArticleclassCategory($result);
            $arr = array('禁用', '启用');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){
            		$str = '';
            		for ($i=0; $i < $vo['count'] ; $i++) { 
            				$str .= '&nbsp;&nbsp;&nbsp;';
            			}	
            		$selectResult[$key]['title'] = $str.'|----'.$vo['title'];

            		$selectResult[$key]['status'] = $arr[$vo['status']];
            		$operate = [
            				'添加' => url('articleclass/articleclassAdd', ['pid' => $vo['id']]),
            				'编辑' => url('articleclass/articleclassEdit', ['id' => $vo['id']]),
            				'删除' => "javascript:articleclassDel('".$vo['id']."')"
            		];           	
            		$selectResult[$key]['operate'] = showOperate($operate);          	
            	}            	
            	// $return['total'] = count($selectResult);  //总数据
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    //添加文章
    public function articleclassAdd()
    {
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            $data['title'] = $param['title'];

            $article = new articleclassModel();
            $find = $article->findarticleclass($data);
            if ($find) {
            	return json(['code' => -1, 'data' => '', 'msg' => '类名重复']);
            	
            }
            $flag = $article->insertArticleclass($param);
            $this->log->addLog($this->logData,'进行了文章分类添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
            $param = input('param.');
            if (!isset($param['pid'])) {
                $param['pid'] = 0;
            }
    	$this->assign(['pid' => $param['pid']]);
        return $this->fetch();
    }

    //编辑文章
    public function articleclassEdit()
    {
    	$article = new articleclassModel();

        if(request()->isPost()){

            $param = input('post.');
            // var_dump($param);exit;	
            $param = parseParams($param['data']);
            
           
            $flag = $article->editArticleclass($param);
            $this->log->addLog($this->logData,'进行了文章分类编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $onearticle= $article->getOneArticleclass($id);      
        
        $this->assign(['id' => $onearticle['id'],'title' => $onearticle['title'],'sort' => $onearticle['sort'], 'status' => $onearticle['status']]);
        return $this->fetch();
    }

    //删除文章
    public function articleclassDel()
    {
        $id = input('param.id');

        $role = new articleclassModel();
        $flag = $role->delArticleclass($id);
        $this->log->addLog($this->logData,'进行了文章分类删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
