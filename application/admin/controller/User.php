<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <123464630@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Db;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\agent\model\tpuser_agentModel;
use think\Request;

class User extends Base
{
    //用户列表
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where='1=1 ';
            //1
//            $where = 'status=0';
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where .= ' and (ims_tpuser.phone = '.$param['searchText'].' or ims_tpuser.id ='.$param['searchText'].')';
            }
            $user = new UserModel();
            $selectResult = $user->getUsersByWhere($where, $offset, $limit);

            // $demo = $user->getUsersDemo($selectResult);
            // var_dump($demo);exit;

            $status = config('user_status');
            // var_dump($status);exit;
            $arr = array('是', '否');
            $arr1 = array('不允许','允许');
            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['last_login_time'] = date('Y-m-d H:i:s', $vo['last_login_time']);

                $selectResult[$key]['status'] = $status[$vo['status']];
                $selectResult[$key]['state'] = $arr[$vo['state']];
                $selectResult[$key]['ispay'] = $arr1[$vo['ispay']];

                $operate = [
                    '编辑' => url('user/userEdit', ['id' => $vo['id']]),
                    '详情' => url('user/detail', ['id' => $vo['id']]),
                    '发币' => url('coin/add_coin', ['uid' => $vo['id']]),
                    '删除' => "javascript:userDel('".$vo['id']."')"
                ];
                $selectResult[$key]['operate'] = showOperate($operate);
                if( 1 == $vo['id'] ){
                	$selectResult[$key]['operate'] = '';
                }
            }

            $return['total'] = $user->getAllUsers($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }


    //用户详情
    public function detail($id){
       $user=UserModel::get($id);
        $user['rtidcount']=$user->get_rtid();
        $user['rtid2count']=$user->get_rtid2();
       $this->assign('user',$user);
       return $this->fetch();
    }

    //用户详情
    public function rtid_list($id,$typ){
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $user = new UserModel();
            $userinfo=$user->where('id',$id)->find();
           if($param['typl']==1){
               $where['rtid']=$userinfo['phone'];
           }else{
               $where['rtid2']=$userinfo['phone'];
           }
//

            $selectResult =   $user->where($where)->limit($offset, $limit)->order('id')->select();
            // $demo = $user->getUsersDemo($selectResult);
            // var_dump($demo);exit;

            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['last_login_time'] = date('Y-m-d H:i:s', $vo['last_login_time']);

            }

            $return['total'] = $user->getAllUsers($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        if($typ==1){
          $name='一级好友';
        }else{
          $name='二级好友';
        }
        $this->assign('type',$typ);
        $this->assign('name',$name);
        return $this->fetch();
    }

    //个人中心
    public function center(){
        // var_dump(session('id'));

        if(request()->isPost()){

                $user = new UserModel();
                $file = request()->file('file');

                $param = input('post.');
                $param = parseParams($param['data']);
                if ($param['password']) {
                    $param['password'] = md5($param['password']);
                } else {
                    unset($param['password']);
                }
                $param['id'] = session('id');
                if ($file) {
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if ($info) {
                        // 成功上传后 获取上传信息
                        // 输出 jpg
                        // echo $info->getExtension();
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        // echo $info->getSaveName();
                        // 输出 42a79759f284b767dfcb2a0197904287.jpg
                        // echo $info->getFilename();
                        $pic = $user->moveOSS($info->getFilename(), $info->getSaveName());
                        $param['pic'] = $pic;
                    } else {
                        // 上传失败获取错误信息
                        // echo $file->getError();
                    }
                }
            unset($param['username']);

            if(!session('soretype')){
                $res = $user->editUser($param);

                if ($res) {
                    if (!empty($pic)) {
                        session('pic', $pic);
                    }
                    $this->log->addLog($this->logData, '进行了用户个人中心编辑操作');
                    return json(['code' => '1', 'data' => '', 'msg' => '添加成功']);

                } else {
                    return json(['code' => '-4', 'data' => '', 'msg' => '添加失败']);
                }
            }else{
                $res = Db::name('tpagent')->where(['id'=>$param['id']])->update($param);
                if ($res) {
                    if (!empty($pic)) {
                        session('pic', $pic);
                    }
                    $this->log->addLog($this->logData, '进行了用户个人中心编辑操作');
                    return json(['code' => '1', 'data' => '', 'msg' => '添加成功']);

                } else {
                    return json(['code' => '-4', 'data' => '', 'msg' => '添加失败']);
                }

            }
        }else{

            if (session('level')) {
                $res = Db::table('ims_tpagent')->where('id',session('id'))->find();
            }else{
                $res = Db::table('ims_tpuser')->where('id',session('id'))->find(); 
            }
            $this->assign('res', $res);
            return $this->fetch();
        }
    }


    //添加用户
    public function userAdd()
    {
        if(request()->isPost()){

            $user = new UserModel();


            $param = input('param.');
            $param = parseParams($param['data']);

            $param['password'] = md5($param['password']);
            $user = new UserModel();
            $flag = $user->insertUser($param);
            $this->log->addLog($this->logData,'进行了用户添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $role = new UserType();
        $arr = array('禁用', '启用');

        $this->assign([
            'role' => $role->getRole(),
            'status' => config('user_status'),
            'state' => $arr
        ]);

        return $this->fetch();
    }

    //编辑角色
    public function userEdit()
    {
        $user = new UserModel();

        if(request()->isPost()){

            $param = input('post.');
            $param = parseParams($param['data']);

            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5($param['password']);
            }
            if($param['rtid']){
                $userinfo=$user->where('phone',$param['rtid'])->find();
                if($userinfo['rtid']){
                    $param['rtid2']=$userinfo['rtid'];
                }
            }

            $flag = $user->editUser($param);
            $this->log->addLog($this->logData,'进行了用户编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $role = new UserType();

        $this->assign([
            'user' => $user->getOneUser($id),
            'status' => config('user_status'),
            'role' => $role->getRole(),
            
        ]);
        return $this->fetch();
    }

    //删除角色
    public function userDel()
    {
        $id = input('param.id');

        $role = new UserModel();
        $flag = $role->delUser($id);
        $this->log->addLog($this->logData,'进行了删除用户操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    //修改密码
    public function editpassword(){
        if(request()->isPost()){
            $param = input('post.');
            $param = parseParams($param['data']);
            $id = session('id');
            $oldpassword = $param['oldpassword'];
            $newpassword = $param['newpassword'];
            $newtwopassword = $param['newtwopassword'];
            $tpuserinfo = Db::name('tpuser')->where('id',$id)->find();
            if(empty($tpuserinfo)){
                return json(['code' => -4, 'data' => '', 'msg' => '找不到该用户']);   
            }
            if(empty($oldpassword)){
                return json(['code' => -4, 'data' => '', 'msg' => '请输入您的旧密码']); 
            }
            if(empty($newpassword)){
                return json(['code' => -4, 'data' => '', 'msg' => '请输入您的新密码']); 
            }
            if($newpassword!=$newtwopassword){
                return json(['code' => -4, 'data' => '', 'msg' => '两次输入密码不一致']);
            }

            if(MD5($oldpassword)!==$tpuserinfo['password']){
                return json(['code' => -4, 'data' => '', 'msg' => '旧密码输入不正确']); 
            }
            $uptpuserpassword = Db::name('tpuser')->where('id',$id)->update(['password' => MD5($newpassword)]);
            if($uptpuserpassword!==false){
                session(null);
                $this->log->addLog($this->logData,'进行了用户修改密码操作');
                return json(['code' => 1, 'data' => url('admin/Login/index'), 'msg' => '密码修改成功，请重新登录']);
            }
            

        }
        return $this->fetch();
    }


    //代理申请列表
    public function agentapplicationlist(){
        if(request()->isAjax()){
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = '1=1';
            $tpuseragent = new tpuser_agentModel();
            $list = $tpuseragent->gettpuseragentByWhere($where, $offset, $limit); 
            $role_type = config('role_type'); 
            $agentstatus = config('agentstatus');
            foreach ($list as $key => $value) {


                // $list[$key]['rolename']    = $role_type[$value['typeid']];
                $list[$key]['agentstatus']    = $agentstatus[$value['status']];
                $list[$key]['addtime']        = date('Y-m-d H:i:s',$value['addtime']);
                $list[$key]['headpic'] = $tpuseragent->headpic($value['picid']);
                if ($value['status'] == 0) {
                    $operate = [
                                '通过审核' => url('user/editagent', ['id' => $value['id'],'status'=>1]),
                                '不通过审核' => url('user/agentadopt', ['id' => $value['id'],'status'=>2]),

                    ];
                    $list[$key]['operate'] = showOperate($operate);
                }



            }
            $return['total'] = $tpuseragent->getAlltpuseragent($where);  //总数据
            $return['rows'] = $list;
            return json($return);            
        }
        return $this->fetch();
    }

    //审核通过，不通过操作
    public function agentadopt(){
        if(request()->isGet()){
            $id = input('param.id');
            $status = input('param.status');
            if(empty($id) || empty($status)){
                return $this->error('参数错误');
            }else{

                 $result = Db::name('tpagent_apply')->where('id',$id)->update(['status' => $status]);


  

                if($result !== false){
                    $content=$status==1?'进行了代理商申请通过审核操作':'进行了代理商申请不通过审核操作';
                    $this->log->addLog($this->logData,$content);
                    return $this->error('操作成功！',url('user/agentapplicationlist'));
                }else{
                    return $this->error('操作失败，请稍后再试！');
                }
            }
        }

    }


    //权限分配
    public function editagent(){
        $tpuseragent = new tpuser_agentModel();
        $id = input('param.id');

        if(request()->isPost()){
            $usertype     = new UserType();
            $param = input('post.');
            $param = parseParams($param['data']);
            $agent = $usertype->applyagent($param['id']);
            $username = $usertype->agentName($agent['username']);
            if ($username) {
                return json(['code' => '-4', 'data' => '', 'msg' => '该用户名已存在']);
                
            }
            
            //验证手机号
            $phone    = $usertype->agentPhone($agent['phone']);
            if ($phone) {
                return json(['code' => '-4', 'data' => '', 'msg' => '该手机号码已申请']);
                
            }

            
            $res = $tpuseragent->insertagent($param);
            if ($res) {
                $this->log->addLog($this->logData,'进行了权限审核通过操作');
                return json(['code' => 1, 'data' => '', 'msg' => '审核通过']);
                
            }else{
                return json(['code' => -4, 'data' => '', 'msg' => '审核失败']);
            }
        }else{
            $soretype = $tpuseragent->soretype();
            $role = db('tprole')->select();
            $this->assign('role', $role);
            $this->assign('sore', $soretype);
            $this->assign('id', $id);
            return $this->fetch();
        }

    }



}
