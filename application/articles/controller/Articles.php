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
namespace app\articles\controller;

use think\Controller;
use app\articles\model\articlesModel;

class Articles extends Controller
{
    //公开文章列表
    public function index()
    {
    	$articleid = input('param.')['articlesid'];  	
    	$articlesModel = new articlesModel();
    	$returnonearticles = $articlesModel->getOneArticles($articleid);
    	
    	$this->assign(['articlename'=>$returnonearticles['articlename'],'articlecontent'=>$returnonearticles['articlecontent'],'articlecreatetime'=>$returnonearticles['articlecreatetime'],]);
    	
        return $this->fetch();
    }
}
