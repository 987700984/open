<?php
namespace app\open\controller;

class Login extends Base
{
    public function index(){
        if(request()->isAjax()){

            $username = input('username');
            $password = input('password');
            $client_type = input('client_type');

            $arr = ['geetest_challenge'=>input('geetest_challenge'),'geetest_validate'=>input('geetest_validate'),'geetest_seccode'=>input('geetest_seccode')];
            $flag = $this->geetest_api2($arr,['user_id'=>$username,'client_type'=>$client_type,'ip_address'=>getIp()]);

            if(!$flag){
                return json(['status'=>0,'msg'=>'验证失败']);
            }
            return json(['status'=>1,'msg'=>'登录成功']);
        }else{
            // 临时关闭当前模板的布局功能
            $this->view->engine->layout(false);
            return $this->fetch();
        }

    }
}