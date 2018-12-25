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
namespace app\appcategory\controller;

use app\admin\controller\Base;;

use app\appcategory\model\appcategoryModel;

class Appcategory extends Base
{
    //分类列表
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
            $appcategory = new appcategoryModel();
            $selectResult = $appcategory->getAppCategoryByWhere($where, $offset, $limit);
            $arr = array('禁用', '启用');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){
                    // $res = $article->getArticlecategory($vo['articlecid']);
            		$operate = [
            				'编辑' => url('appcategory/appcategoryEdit', ['id' => $vo['id']]),
            				'删除' => "javascript:appcategoryDel('".$vo['id']."')"
            		];           	
                    $selectResult[$key]['operate'] = showOperate($operate);             
                    $selectResult[$key]['status'] = $arr[$vo['status']];             
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);             
                    $selectResult[$key]['endtime'] = date('Y-m-d H:i:s', $vo['endtime']);             
            		// $selectResult[$key]['articlecid'] = $res[0]['name'];          	
            	}            	
            	$return['total'] = count($selectResult);  //总数据
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    //添加分类
    public function appcategoryAdd()
    {   
        $app = new appcategoryModel();

        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            $param['addtime'] = time();
            $param['endtime'] = time();
            $flag = $app->insertAppcategory($param);
            $this->log->addLog($this->logData,'进行了应用分类添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();        
    }

    //编辑分类
    public function appcategoryEdit()
    {
    	$appcategory = new appcategoryModel();

        if(request()->isPost()){

            $param = input('post.');
            $param = parseParams($param['data']);
            $param['endtime'] = time();
            
            // $param['articlemodperson'] = session("id");
            // $param['articlemodtime'] = date('Y-m-d H:i:s');
            $flag = $appcategory->editappcategory($param);
            $this->log->addLog($this->logData,'进行了应用分类编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $oneAppCategory= $appcategory->getOneAppcategory($id);      
                // $selectResult = $article->category();
        // $category = $article->categoryArticle($selectResult);
        // foreach ($category as &$value) {
        //     $str = '';
        //     for ($i=0; $i < $value['count']; $i++) { 
        //         $str .= '&nbsp;&nbsp;';
        //     }
        //     $value['name'] = $str.'|--'.$value['name'];
        // }
        // $this->assign(['category' => $category]);
        $this->assign(['id' => $oneAppCategory['id'],'name' => $oneAppCategory['name'],'status' => $oneAppCategory['status']]);
        return $this->fetch();
    }

    //删除分类
    public function appcategoryDel()
    {
        $id = input('param.id');

        $role = new appcategoryModel();
        $flag = $role->delAppCategory($id);
        $this->log->addLog($this->logData,'进行了应用分类删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
