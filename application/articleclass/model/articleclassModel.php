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
namespace app\articleclass\model;

use think\Model;

class articleclassModel extends Model
{
	protected $table = 'ims_news_type';
	
	/**
	 * 根据搜索条件获取文章列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getArticleclassByWhere($where)
	{
		return $this->field('ims_news_type.*')
		->where($where)->order('sort')->select();
	}

    /**
    *无限级
    *@param $param
    */
    public function getArticleclassCategory($param, $pid = 0, $count = 0){
        static $res = array();
        if ($param) {
            foreach ($param as $key => $value) {
                if ($value['pid'] == $pid) {
                    $value['count'] = $count + 2;
                    $res[] = $value;
                    $this->getArticleclassCategory($param, $value['id'], $value['count']);
                }
            }
            return $res;
        }
    }

    /**
     * 新增文章
     * @param $param
     */
    public function insertArticleclass($param)
    {
        try{
            // var_dump($param);exit;
            $result =  $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 0, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     *分类name判断
     *@param $param
     */
    public function findArticleclass($param){
        return $this->where($param)->find();

    }

    
    /**
     * 编辑文章
     * @param $param
     */
    public function editArticleclass($param)
    {
    	try{
    
    		$result = $this->save($param, ['id' => $param['id']]);
    
    		if(false === $result){
    			// 验证失败 输出错误信息
    			return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
    		}else{
    
    			return ['code' => 0, 'data' => '', 'msg' => '编辑成功'];
    		}
    	}catch( PDOException $e){
    		return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 删除文章
     * @param $articleid
     */
    public function delArticleclass($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 根据文章ID获取文章内容
     * @param $articleid
     */
    public function getOneArticleclass($id)
    {
        return $this->where('id', $id)->find();
    }

}