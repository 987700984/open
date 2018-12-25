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

class UserType extends Model
{
    protected  $table = 'ims_tprole';

    /**
     * 根据搜索条件获取角色列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRoleByWhere($where, $offset, $limit)
    {

        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的角色数量
     * @param $where
     */
    public function getAllRole($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入角色信息
     * @param $param
     */
    public function insertRole($param)
    {
        try{

            $result =  $this->validate('RoleValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加角色成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑角色信息
     * @param $param
     */
    public function editRole($param)
    {
        try{

            $result =  $this->validate('RoleValidate')->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑角色成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据角色id获取角色信息
     * @param $id
     */
    public function getOneRole($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除角色
     * @param $id
     */
    public function delRole($id)
    {
        try{

            $this->where('id', $id)->delete();
            $this->name('tpcommissions')->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除角色成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //获取所有的角色信息
    public function getRole()
    {
        return $this->select();
    }

    //获取角色的权限节点
    public function getRuleById($id)
    {
        $res = $this->field('rule')->where('id', $id)->find();

        return $res['rule'];
    }

    /**
     * 分配权限
     * @param $param
     */
    public function editAccess($param)
    {
        try{
            $this->save($param, ['id' => $param['id']]);
            return ['code' => 1, 'data' => '', 'msg' => '分配权限成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 获取角色信息
     * @param $id
     */
    public function getRoleInfo($id){

        $result = db('tprole')->where('id', $id)->find();
        if(empty($result['rule'])){
            $where = '';
        }else{
            $where = 'id in('.$result['rule'].')';
        }
        $res = db('tpnode')->field('control_name,action_name')->where($where)->select();
        foreach($res as $key=>$vo){
            if('#' != $vo['action_name']){
                $result['action'][] = $vo['control_name'] . '/' . $vo['action_name'];
            }
        }

        return $result;
    }

    public function setInterestAttr($param)
    {
        return $param*10000;
    }

    public function getInterestAttr($param)
    {
        return $param/10000;
    }

    public function code($length = 6, $time = 5){
        $data = array();
        $data['phone'] = input('phone');
        $data['code'] = rand(pow(10,($length-1)), pow(10,$length)-1);
        $data['addtime'] = time();
        $data['endtime'] = time()+(60 * $time);
        db('tpcode')->insert($data);
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
  
        $smsConf = array(
            'key'   => config('code.key'), //您申请的APPKEY
            'mobile'    => $data['phone'], //接受短信的用户手机号码
            'tpl_id'    => config('code.tpl_id'), //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>'#code#='.$data['code'].'&#company#=WEToken' //您设置的模板变量，根据实际情况修改
        );
        // $juhecurl = new \juhecurl();
        $content = juhecurl($sendUrl,$smsConf,1);
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

    public function uploadPic($data){
        return db('tppic')->insertGetId($data);
    }

    //图片保存

    public function picinsert($pic = array()){
        if ($pic) {
            $arr = array();
            foreach ($pic as $key => $value) {
                if ($value) {
                    
                    $data = array(
                            'type'    => $key,
                            'pic'     => $value,
                            'addtime' => time()
                        );
                    $arr[] = db('tppic')->insertGetId($data);
                }
            }
            return serialize($arr);
        }
    }

    //判断用户代理username
    public function agentName($name){
        $username = db('tpuser')->where('username', $name)->select();
        if (!$username) {
            $username = db('tpagent')->where('username', $name)->select();
        }
        return $username;
    }

    //验证手机号重复
    public function agentPhone($mobile){

        $phone = db('tpagent')->where('phone', $mobile)->select();
        
        return $phone;
    }

    //验证码
    public function agentcode($phone, $code){
        $time = time();
        $tpcode = db('tpcode')->where("endtime > ".$time." and phone =".$phone.' and status!=1')->order('addtime desc, id desc')->find();
        // var_dump($tpcode);exit;
        if ($tpcode['code'] == $code) {
            return 1;
        }
    }

    //代理申请添加
    public function agentApply($param, $code){
        $apply = db('tpagent_apply')->insertGetId($param);
        db('tpcode')->where('phone='.$param['phone'].' and code='.$code)->update(['status' => 1]);
        return $apply;
    }
    public function applyagent($id){
        return db('tpagent_apply')->where('id='.$id)->find();
    }

}