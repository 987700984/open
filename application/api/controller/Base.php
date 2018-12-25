<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use \think\Loader;
use afs\Request\V20180112 as Afs;
use app\admin\extend\alipay\AopClient;
use app\admin\extend\alipay\AlipayFundTransToaccountTransferRequest;

class Base extends Controller{
  	protected $data = array(
		'status' => 0,
		'msg' => '错误',

	);
	protected function _initialize () {
        $sid = get_input_data('sid');

        if(isset($sid)){
            $s = Db::name('tpsoretype')->field('name,status')->where(['id'=>$sid])->find();

            if(isset($s)){
                if($s['status'] != 1){
                    $this->data['msg'] = $s['name'].'已关闭';
                    echo json_encode($this->data,JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }else{

                $this->data['msg'] = "币种不存在";
                echo json_encode($this->data,JSON_UNESCAPED_UNICODE);
                exit();
            }
        }

	}

	//极验验证
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

	//阿里云滑动验证
	protected function afs_api(){
        Loader::import('aliyun-php-sdk-core.Config', EXTEND_PATH);

        $appkey = (get_input_data('appkey'));
        $csessionid = (get_input_data('csessionid'));
        $nc_token = (get_input_data('nc_token'));
        $scene = (get_input_data('scene'));
        $sig = (get_input_data('sig'));

        //YOUR ACCESS_KEY、YOUR ACCESS_SECRET请替换成您的阿里云accesskey id和secret
        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", "LTAIFmCocQrraBP7", "Luah9P6QUi89FlUVeLYLKJa0nV0Q9x");
        $client = new \DefaultAcsClient($iClientProfile);
        \DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "afs", "afs.aliyuncs.com");

        $request = new Afs\AuthenticateSigRequest();
        $request->setSessionId($csessionid);// 必填参数，从前端获取，不可更改，android和ios只变更这个参数即可，下面参数不变保留xxx
        $request->setToken($nc_token);// 必填参数，从前端获取，不可更改
        $request->setSig($sig);// 必填参数，从前端获取，不可更改
        $request->setScene($scene);// 必填参数，从前端获取，不可更改
        $request->setAppKey($appkey);//必填参数，后端填写
        $request->setRemoteIp(getIp());//必填参数，后端填写
        $response = $client->doAction($request);//response的code枚举：100验签通过，900验签失败

        $res = json_decode($response->getBody(),true);

        if($res['Code'] == 100){
            return true;
        }else{
            return false;
        }
    }



}