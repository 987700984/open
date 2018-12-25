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
namespace app\agent\model;

use think\Model;

class agentModel extends Model

{
	protected $table = 'ims_tpuser';
	// protected $uploadPath = 'Uploads/';
	
	public function getOneAgent($id)
	{
		return $this->where('id', $id)->find();
	}
	//oss上传
	public function moveOSS($pic, $file, $age = array()){
		$ossClient	= new \OSS\OssClient(config('OSS_KEY'), config('OSS_SECRET'), config('OSS_ENDPOINT'));
		// var_dump($ossClient);exit;
		$bucket		= config('OSS_BUCKET');
		// 删除旧文件
		// if (! empty($this->orgData[$pk])){
		// 	$oldFile = $this->where(array($pk => $this->orgData[$pk]))->getField($field);
		// 	if( !empty($oldFile) ){
		// 		$ossClient->deleteObject($bucket, $oldFile);
		// 	}
		// }
		// if ($age) {
		// 		$ossClient->deleteObject($bucket, $age['pic']);

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
	//币种列表
	public function soretype(){
		set_key();
		$sql = "select id,name from ims_tpsoretype where status = 1";
		return $this->query($sql);
	}

	//代理添加
	public function insertAgent($data){
		return $this->table('ims_tpagent')->insert($data);
	}
	public function agentDel($id)
    {
    	try{
    
    		$this->table('ims_tpagent')->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }    

}