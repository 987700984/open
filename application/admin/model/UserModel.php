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
namespace app\admin\model;

use think\Model;

class UserModel extends Model
{
    protected $table = 'ims_tpuser';

    /**
     * 与userType表关联
     * @param $param
     */
    public function ustype()
    {
        return $this->belongsTo('UserType','typeid','id');
    }
    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getUsersByWhere($where, $offset, $limit)
    {
        return $this->field('ims_tpuser.*,rolename')
            ->join('ims_tprole', 'ims_tpuser.typeid = ims_tprole.id','LEFT')
            ->where($where)->limit($offset, $limit)->order('id')->select();
    }
    //一级好友人数
    public function  get_rtid(){
        return $this->where('rtid',$this->phone)->count();
    }
    //二级好友人数
    public function  get_rtid2(){
        return $this->where('rtid2',$this->phone)->count();
    }
    public function moveOSS($pic, $file, $age = array()){
        $ossClient  = new \OSS\OssClient(config('OSS_KEY'), config('OSS_SECRET'), config('OSS_ENDPOINT'));
        $bucket     = config('OSS_BUCKET');
        // 删除旧文件
        // if (! empty($this->orgData[$pk])){
        //  $oldFile = $this->where(array($pk => $this->orgData[$pk]))->getField($field);
        //  if( !empty($oldFile) ){
        //      $ossClient->deleteObject($bucket, $oldFile);
        //  }
        // }
        if ($age) {
                $ossClient->deleteObject($bucket, $age['pic']);

        }
    
        // 开始上传
        $file = ROOT_PATH . 'public' . DS . 'uploads'. DS .$file;
        $pic = date('ymd').'/'.$pic;
        try {
            // $ossClient->putObject(config('OSS_BUCKET'), $pic, $content);
            $ossClient->uploadFile($bucket, $pic, $file);
            return 'http://'.config('OSS_BUCKET').'.img'. substr(config('OSS_ENDPOINT'), 3).'/'. $pic;
        } catch (\OSS\Core\OssException $e) {
            //var_dump(__FUNCTION__ . ": FAILED\n");
            // var_dump($e->getMessage() . "\n");
            return false;
        }
    }

        /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     */
    public function getUsersDemo()
    {
        $user = $this->select();
        $demo = $this->connect('mysql://btcimmysql:DCpKPCJhxD@120.79.77.111:3306/btcimmysql#utf8');
        $member = $demo->table('lb_member')->select();
        $id   = array();
        foreach ($user as $k => $v) {
            $id[] = $v['id']; 
        }
        $this->connect('mysql://open:MJZkS8dPj8iGxEAG@localhost:3306/open#utf8');
        foreach ($member as $key => $value) {
            if (!in_array($value['id'], $id)) {
                $arr = array(
                        'id' => $value['id'],                        
                        'username' => $value['nickname'],                        
                        'password' => $value['password'],                        
                        'loginnum' => $value['login_count'],                        
                        'last_login_ip' => '127.0.0.1',                        
                        'last_login_time' => $value['addtime'],                        
                        'real_name' => $value['username'],                        
                        'phone' => $value['phone'],                        
                        'jifen' => $value['jifen'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                    );
                $this->table('ims_tpuser')->insert($arr);
            }
        }
        return $member;
    }


    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertUser($param)
    {
        try{

            $result =  $this->validate('UserValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     */
    public function editUser($param)
    {
        try{

            if (session('level')) {
                $str = '';
                $id = $param['id'];
                unset($param['id']);
                foreach ($param as $key => $value) {
                    if ($str) {
                        $str .= ',';
                    }
                    $str .= $key."='".$value."'";
                }
                $sql = "update ims_tpagent set ".$str." where id=".$id;
                $result = $this->query($sql);
                var_dump($param);exit;
            }else{

                $result =  $this->validate('UserValidate')->save($param, ['id' => $param['id']]);
            }


            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    
    public function editUserCardcount($param)
    {
        try{
    
            $result =  $this->save($param, ['id' => $param['id']]);
    
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
    
                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }

    public function getOneUserByuserid($userid)
    {
        return $this->where('userid', $userid)->find();
    }
    
    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        try{

            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除管理员成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //分享图片列表
    public function share($where, $offset, $limit){
        return db('share')->where($where)->limit($offset, $limit)->select();
    }
    public function getAllshare($where){
        return db('share')->where($where)->select();
    }

    //编辑数据
    public function getOneshare($id){
        return db('share')->where('id='.$id)->find();
    }

    /**
     * 删除
     * @param $ordersid
     */
    public function delshare($id)
    {
        try{
    
            db('share')->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
    
        }catch( PDOException $e){
            return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
        }
    }    

    //清空模板
    public function tempdel(){
        try{
    
            $files = glob('./uploads/share/tmp/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            return ['code' => 1, 'data' => '', 'msg' => '清空成功'];
    
        }catch( PDOException $e){
            return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
        } 
    }

}