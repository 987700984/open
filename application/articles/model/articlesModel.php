<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\articles\model;

use think\Model;

class articlesModel extends Model
{
	protected $table = 'ims_tparticle';
    
    /**
     * 根据文章ID获取文章内容
     * @param $articleid
     */
    public function getOneArticles($articleid)
    {
        return $this->where('articleid', $articleid)->find();
    }

}