<?php
namespace app\api\controller;
use app\integral\model\integralModel;
use think\Db;
use think\Request;


class Common extends Base{
	protected function _initialize() {

		parent::_initialize();
		$user = session('user');
		if(!$user){
			$request = Request::instance();
			if (Request::instance()->isPost()) {
                $token = urlencode(input('get.token'));
                if(empty($token)){
                    $token = get_input_data('token');
                }
			}else{

//                echo input('token');die;
				$token = urlencode(input('token'));


			}

			if (!$token) {
				$this->data['status'] = 0;
				$this->data['msg']='未登录';

				exit(json_encode($this->data,JSON_UNESCAPED_UNICODE));
			}else{
			    $token = urldecode($token);dump($token);
			    $token = str_replace(' ','+',$token);
				$res = base64_decode(authcode($token));

				$arr = json_decode($res, 'true');

				// dump($arr);die;
				$password = md5($arr['password']);
				if ($arr['username'] != '' && $arr['password'] != '') {

					$user = db::name('tpuser')->where(array('phone' => $arr['username'], 'password' => $password))->find();
				}else{
					$user = '';
				}
				if ($user) {

                    if($user['state'] == 0){
                        $this->data['msg'] = '帐号已封禁,请联系客服';
                        $this->ajaxReturn($this->data);
                    }
                    db::name('tpuser')->where('id',$user['id'])->update(['last_login_ip'=>getIp(),'last_login_time'=>time()]);
                    session('user',$user);
				}else{
					$this->data['status'] = 0;

					$this->data['msg']='token错误';

					exit(json_encode($this->data,JSON_UNESCAPED_UNICODE));
				}
			}

		}		

	}
}