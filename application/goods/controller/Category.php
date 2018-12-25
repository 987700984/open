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
namespace app\goods\controller;

use app\admin\controller\Base;;

use app\goods\model\categoryModel;
use think\Db;

class Category extends Base
{
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

            $category = new categoryModel();
            $selectResult = $category->getCategoryByWhere($where, $offset, $limit);
            
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){	
            		$operate = [
            				'编辑' => url('category/Edit', ['id' => $vo['id']]),
            				'删除' => "javascript:Del('".$vo['id']."')"
            		];           	
            		$selectResult[$key]['operate'] = showOperate($operate);   
            		$selectResult[$key]['addtime']=date('Y-m-d H:i:s',$vo['addtime']);
            	}            	
            	$return['total'] = $category->getAllCategory($where);
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    public function Add()
    {
        if(request()->isPost()){

            $param = input('param.');  
            $param = parseParams($param['data']);
           	$param['addtime'] = time();
            
            $category = new categoryModel();
            $flag = $category->insertCategory($param);
            $this->log->addLog($this->logData,'进行了商品分类添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }

    public function Edit()
    {
    	$category = new categoryModel();

        if(request()->isPost()){

            $param = input('post.');
            $param = parseParams($param['data']);           
            
         

            $flag = $category->editCategory($param);
            $this->log->addLog($this->logData,'进行了商品分类编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $onecategory= $category->getOneCategory($id);      
        $rolelist = Db::name('tprole')->select();
        $this->assign(['title' => $onecategory['title'],'id' => $onecategory['id'],'orderdisplay' => $onecategory['orderdisplay']]);
        $this->assign('rolelist',$rolelist);
        return $this->fetch();
    }

    public function Del()
    {
        $goodsid = input('param.id');

        $role = new categoryModel();
        $flag = $role->delCategory($goodsid);
        $this->log->addLog($this->logData,'进行了商品分类删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
