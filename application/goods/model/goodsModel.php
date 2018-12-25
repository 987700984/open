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
namespace app\goods\model;

use think\Model;

class goodsModel extends Model
{
	protected $table = 'ims_tpgoods';
	
	/**
	 * 根据搜索条件获取商品列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getGoodsByWhere($where, $offset, $limit)
	{
		return $this->field('ims_tpgoods.*')
		->where($where)->limit($offset, $limit)->order('goodsid desc')->select();
	}
    /**
     * 新增商品
     * @param $param
     */
    public function insertGoods($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 0, 'data' => '', 'msg' => '添加成功','id'=>$this->goodsid];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    
    /**
     * 编辑商品
     * @param $param
     */
    public function editGoods($param)
    {
    	try{
    
    		$result = $this->save($param, ['goodsid' => $param['goodsid']]);
    
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
     * 删除商品
     * @param $goodsid
     */
    public function delGoods($goodsid)
    {
    	try{
    
    		$this->where('goodsid', $goodsid)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }
    
    /**
     * 根据商品ID获取商品内容
     * @param $goodsid
     */
    public function getOneGoods($goodsid)
    {
        return $this->where('goodsid', $goodsid)->find();
    }
    
    /**
     * 根据搜索条件获取所有的商品
     * @param $where
     */
    public function getAllGoods($where)
    {
    	return $this->where($where)->count();
    }
    
    public function getGoods()
    {
        return $this->select();
    }

        //商品分类
    public function allCategory()
    {
        return $this->table('ims_tpgoods_category')->select();
        
        
    }

            //商品分类
    public function oneCategory($id)
    {
        return $this->table('ims_tpgoods_category')->where('id='.$id)->value('title');
        
        
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

}