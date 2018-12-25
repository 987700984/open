<?php
namespace app\open\controller;
use think\Controller;
use think\Request;

class Base extends Controller
{
	protected function _initialize (){

    }

    protected function geetest_api2($arr,$data){
        import('geetest.config', EXTEND_PATH);
        import('geetest.geetestlib', EXTEND_PATH);

        $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);

        $tmp = cache('xw_'.$data['user_id']);
        if ($tmp['gtserver'] == 1) {   //服务器正常
            $result = $GtSdk->success_validate($arr['geetest_challenge'], $arr['geetest_validate'], $arr['geetest_seccode'], $data);
            if ($result) {
                return 1;
            } else{
                return 0;
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($arr['geetest_challenge'], $arr['geetest_validate'], $arr['geetest_seccode'])) {
                return 1;
            }else{
                return 0;
            }
        }
    }

}