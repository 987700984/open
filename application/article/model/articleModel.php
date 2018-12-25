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
namespace app\article\model;

use think\Model;

class articleModel extends Model
{
	protected $table = 'ims_news';
	
	/**
	 * 根据搜索条件获取文章列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getArticleByWhere($where, $offset, $limit)
	{
		return $this->field('ims_news.*')
		->where($where)->limit($offset, $limit)->order('id desc')->select();
	}

    public function allarticle($where){
        return $this->field('ims_news.id')->where($where)->select();
    }

    public function moveOSS($pic, $file, $age = array()){
        $ossClient  = new \OSS\OssClient(config('OSS_KEY'), config('OSS_SECRET'), config('OSS_ENDPOINT'));
        // var_dump($ossClient);exit;
        $bucket     = config('OSS_BUCKET');
        // 删除旧文件
        // if (! empty($this->orgData[$pk])){
        //  $oldFile = $this->where(array($pk => $this->orgData[$pk]))->getField($field);
        //  if( !empty($oldFile) ){
        //      $ossClient->deleteObject($bucket, $oldFile);
        //  }
        // }
        // if ($age) {
        //      $ossClient->deleteObject($bucket, $age['pic']);

        // }
    
        // 开始上传
        $file = ROOT_PATH . 'public' . DS . 'uploads'. DS .$file;
        $pic = date('ymd').'/'.$pic;
        try {
            // $ossClient->putObject(config('OSS_BUCKET'), $pic, $content);
            $a = $ossClient->uploadFile($bucket, $pic, $file);
            return 'http://'.config('OSS_BUCKET').'.img'. substr(config('OSS_ENDPOINT'), 3).'/'. $pic;
        } catch (\OSS\Core\OssException $e) {
            //var_dump(__FUNCTION__ . ": FAILED\n");
            // var_dump($e->getMessage() . "\n");
            return false;
        }
    }

    /**
     * 新增文章
     * @param $param
     */
    public function insertArticle($param)
    {
        try{

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
     * 编辑文章
     * @param $param
     */
    public function editArticle($param)
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
    public function delArticle($articleid)
    {
    	try{
    
    		$this->where('id', $articleid)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
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
        $sql = "select * from ims_news_type where status = 1";
        $res = $this->query($sql);
        return $res;
    }

    /**
    *分类
    *@param $param
    */
    public function getArticlecategory($id){
        $sql = "select * from ims_news_type where id = ".$id;
        $res = $this->query($sql);
        return $res;
    }

    /**
     * 根据文章ID获取文章内容
     * @param $articleid
     */
    public function getOneArticle($articleid)
    {
        return $this->where('id', $articleid)->find();
    }

}