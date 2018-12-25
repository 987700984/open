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
namespace app\appcategory\model;

use think\Model;

class appcategoryModel extends Model
{
	protected $table = 'ims_tpapp_category';
	
	/**
	 * 根据搜索条件获取文章列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getAppCategoryByWhere($where, $offset, $limit)
	{
        // var_dump($where);
		return $this->where($where)->limit($offset, $limit)->select();
	}

    /**
     * 新增文章
     * @param $param
     */
    public function insertAppcategory($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    
    /**
     * 编辑文章
     * @param $param
     */
    public function editappcategory($param)
    {
    	try{
    
    		$result = $this->save($param, ['id' => $param['id']]);
    
    		if(false === $result){
    			// 验证失败 输出错误信息
    			return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
    		}else{
    
    			return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
    		}
    	}catch( PDOException $e){
    		return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 删除文章
     * @param $articleid
     */
    public function delAppCategory($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
    *无限级
    *@param $param
    */
    public function categoryArticle($param, $pid = 0, $count = 0){
        static $res = array();
        if ($param) {
            foreach ($param as $key => $value) {
                if ($value['pid'] == $pid) {
                    $value['count'] = $count + 2;
                    $res[] = $value;
                    $this->categoryArticle($param, $value['id'], $value['count']);
                }
            }
            return $res;
        }
    }

    /**
    *select分类
    *@param $param
    */
    public function category(){
        $sql = "select * from ims_tparticle_category where status = 1";
        $res = $this->query($sql);
        return $res;
    }

    /**
    *分类
    *@param $param
    */
    public function getArticlecategory($id){
        $sql = "select * from ims_tparticle_category where id = ".$id;
        $res = $this->query($sql);
        return $res;
    }

    /**
     * 根据文章ID获取文章内容
     * @param $articleid
     */
    public function getOneAppcategory($id)
    {
        return $this->where('id', $id)->find();
    }

}