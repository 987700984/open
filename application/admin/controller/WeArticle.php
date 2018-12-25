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
namespace app\admin\controller;

use app\admin\controller\Base;;

use app\article\model\articleModel;

class WeArticle extends Base
{
    //wetoken列表
    public function index()
    {
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            $where['identify'] =['eq',9];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where['title'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $arr = array('不推荐', '推荐');
            $article = new articleModel();
            $selectResult = $article->getArticleByWhere($where, $offset, $limit);
            if(count($selectResult) > 0){                   
                foreach($selectResult as $key=>$vo){
                    $res = $article->getArticlecategory($vo['identify']);
                    $operate = [
                            // '预览' => "javascript:articleLook('".$vo['id']."')",
                            '编辑' => url('WeArticle/articleEdit', ['articleid' => $vo['id']]),
                            '删除' => "javascript:articleDel('".$vo['id']."')"
                    ];              
                    $selectResult[$key]['operate'] = showOperate($operate);
                    if(isset($res)){
                        $selectResult[$key]['identify'] = $res[0]['title'];
                    }
                    $selectResult[$key]['recommend'] = $arr[$vo['recommend']];             
            		// $selectResult[$key]['status'] = $arr[$vo['status']];          	
            	}            	
                $count = $article->allarticle($where);
            	$return['total'] = count($count);  //总数据
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    //添加文章
    public function articleAdd()
    {   
        $article = new articleModel();

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
                    $pic = $article->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $param['addtime'] = time();
            $param['identify'] =9;
            $flag = $article->insertArticle($param);
            $this->log->addLog($this->logData,'进行了wetoken公告添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $selectResult = $article->category();
        $category = $article->categoryArticle($selectResult);
        foreach ($category as &$value) {
            $str = '';
            for ($i=0; $i < $value['count']; $i++) { 
                $str .= '&nbsp;&nbsp;';
            }
            $value['title'] = $str.'|--'.$value['title'];
        }
        $this->assign(['category' => $category]);
        return $this->fetch();
    }

    //编辑文章
    public function articleEdit()
    {
    	$article = new articleModel();

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
                       

                        $pic = $article->moveOSS($info->getFilename(), $info->getSaveName());

                        $param['pic'] = $pic;
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                    }
                }
            // $param['articlemodperson'] = session("id");
            // $param['addtime'] = time();
            $flag = $article->editArticle($param);
            $this->log->addLog($this->logData,'进行了wetoken公告编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $articleid = input('param.articleid'); 
        $onearticle= $article->getOneArticle($articleid);      
                $selectResult = $article->category();
        $category = $article->categoryArticle($selectResult);
        foreach ($category as &$value) {
            $str = '';
            for ($i=0; $i < $value['count']; $i++) { 
                $str .= '&nbsp;&nbsp;';
            }
            $value['title'] = $str.'|--'.$value['title'];
        }
        $this->assign(['category' => $category]);
        $this->assign(['id' => $onearticle['id'],'identify' => $onearticle['identify'],'title' => $onearticle['title'],'content' => $onearticle['content'], 'pic' => $onearticle['pic'], 'recommend' => $onearticle['recommend']]);
        return $this->fetch();
    }

    //删除文章
        public function articleDel()
    {
        $articleid = input('param.articleid');

        $role = new articleModel();
        $flag = $role->delArticle($articleid);
        $this->log->addLog($this->logData,'进行了wetoken公告删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
