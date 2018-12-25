<?php
namespace app\api\controller;

use app\api\model\apiModel;
use think\Request;
use \think\Image;
use \think\Captcha;
use \think\Loader;
use think\Db;

class Index extends Base{
	public function index(){
        $key = get_input_data('key');
        if ($key != '123456789') {
            return;
        }
		return $this->fetch();

	}

	// 发送验证码。
    public function send(){
        $version = strtolower(get_input_data('version'));

	    if($version == 'v3'){
            $phone = get_input_data('phone');
            $type = intval(get_input_data('type')); //应用场景 0未注册 1已注册

            if(!preg_match("/^1\d{10}$/",$phone)){
                $this->data['msg'] = '手机格式不正确';
                return json($this->data);
            }

            $code = rand(100000,999999);

            if(empty($phone)){
                $this->data['msg'] = '手机号为空';
                return json($this->data);
            }

            $flag = $this->afs_api();

            if(!$flag){
                $this->data['msg'] = '验证失败';
                return json($this->data);
            }
            $check = db::name('tpuser')->field('id,phone')->where('phone='.$phone)->find();

            if($check){
                if($type == 0){
                    $this->data['msg'] = '手机号已注册';
                    return json($this->data);
                }
            }else{
                if($type == 1){
                    $this->data['msg'] = '手机号未注册';
                    return json($this->data);
                }
            }

//            //IP检测
//            $ip = getIp();
//            if(cache($ip)){
//                $num = cache($ip)+1;
//                cache($ip,$num);
//                if($num>5){
//                    $this->data['msg'] = '操作太频繁';
//                    return json($this->data);
//                }
//            }else{
//                cache($ip,1,300);
//            }


            $result = code($phone,$code);

            if($result == 2){
                $this->data['msg'] = '操作太频繁';
                return json($this->data);
            }elseif ($result == 1){

                $this->data['status'] = 1;
                $this->data['msg'] = '发送成功';
                return json($this->data);
            }else{
                $this->data['msg'] = '发送失败';
                return json($this->data);
            }
        }else{
            $phone = get_input_data('phone');
            $client_type = get_input_data('client_type');//验证码类型
            $type = intval(get_input_data('type')); //应用场景 0未注册 1已注册

            if(!preg_match("/^1\d{10}$/",$phone)){
                $this->data['msg'] = '手机格式不正确';
                return json($this->data);
            }

            $code = rand(100000,999999);

            if(empty($phone)){
                $this->data['msg'] = '手机号为空';
                return json($this->data);
            }
            $arr = ['geetest_challenge'=>get_input_data('geetest_challenge'),'geetest_validate'=>get_input_data('geetest_validate'),'geetest_seccode'=>get_input_data('geetest_seccode')];
            $flag = $this->geetest_api2($arr,['user_id'=>$phone,'client_type'=>$client_type,'ip_address'=>getIp()]);

            if(!$flag){
                $this->data['msg'] = '验证失败';
                return json($this->data);
            }
            $check = db::name('tpuser')->field('id,phone')->where('phone='.$phone)->find();

            if($check){
                if($type == 0){
                    $this->data['msg'] = '手机号已注册';
                    return json($this->data);
                }
            }else{
                if($type == 1){
                    $this->data['msg'] = '手机号未注册';
                    return json($this->data);
                }
            }

            //IP检测
            $ip = getIp();
            if(cache($ip)){
                $num = cache($ip)+1;
                cache($ip,$num);
                if($num>5){
                    $this->data['msg'] = '操作太频繁';
                    return json($this->data);
                }
            }else{
                cache($ip,1,300);
            }


            $result = code($phone,$code);

            if($result == 2){
                $this->data['msg'] = '操作太频繁';
                return json($this->data);
            }elseif ($result == 1){

                $this->data['status'] = 1;
                $this->data['msg'] = '发送成功';
                return json($this->data);
            }else{
                $this->data['msg'] = '发送失败';
                return json($this->data);
            }
        }

    }
    // 注册
    public function ajaxreg(){
        $data['username'] = get_input_data('nickname');
    	$data['phone'] = get_input_data('phone');
    	$password = get_input_data('password');
        $data['rtid'] = get_input_data('rtid');
    	$sid = get_input_data('sid','7');
    	$code = get_input_data('code');

    	//默认推荐人
        if(empty($data['rtid'])){
            $data['rtid'] = Db::name('config')->where(['id'=>3])->value('content');
        }

        if(empty($data['phone'])){
            $this->data['msg'] = '手机号为空';
            return json($this->data);            
        }
        if(empty($password)){
            $this->data['msg'] = '密码为空';
            return json($this->data);            
        } 
        if(empty($code)){
            $this->data['msg'] = '短信验证码为空';
            return json($this->data);            
        }


        if(!check_code($data['phone'],$code)){
              $this->data['msg'] = '短信验证码错误';
              return json($this->data);

        }

    	$check = db::name('tpuser')->field('id,phone')->where('phone='.$data['phone'])->find();
    	if($check){
            $this->data['msg'] = '手机号已注册';
            return json($this->data); 
    	}
        if(!empty($data['rtid'])){
            $t1 = db::name('tpuser')->field('id,phone,rtid')->where('id='.$data['rtid'])->find(); //推荐人
            $data['rtid'] = $t1['phone'];
            $data['rtid2'] = $t1['rtid'];
        }
        //注册
        $soretype = db::name('tpsoretype')->where('id=-1 and status=1')->find();
        $zhuce = $soretype['lv1'];
        $tj1 = $soretype['lv2'];
        $tj2 = $soretype['lv3'];

        $data['password'] = MD5($password);
        $data['addtime'] = time();


        $userId = db::name('tpuser')->insertGetId($data);
        //注册流水
        if($userId){
            $this->setCoin($userId,'lv1');

            //一级推荐
            if(!empty($t1)){
                $this->setCoin($t1['id'],'lv2', $userId);
                // 二级推荐
                if(!empty($t1['rtid'])){
                    // $api->candy($t1['rtid'], $tj2);
                    $rt2 = db::name('tpuser')->where('phone='.$t1['rtid'])->value('id');

                    $m2['uptime'] = time();

                    $this->setCoin($rt2,'lv3', $userId);               
                }
            }
            $this->data['status'] = 1;
            $this->data['msg'] = '注册成功';
            return json($this->data);
        }
        $this->data['msg'] = '注册失败';
        return json($this->data); 
    }

    private function setCoin($uid,$lv, $userid=0){
        $type = db::name('tpsoretype')->where('status=1')->select();
        if($type){
            foreach ($type as $key => $value) {
                $integral = db::name('tpintegral');
                $r = $integral->where('uid='.$uid.' AND sid='.$value['id'])->find();
                if($r){
                    $integral->where('uid='.$r['uid'].' and sid='.$value['id'])->setInc('integral',$value[$lv]);
                    $integral->where('uid='.$r['uid'].' and sid='.$value['id'])->setField('addtime',time());
                }else{
                    $s['uid'] = $uid;
                    $s['sid'] = $value['id'];
                    $s['integral'] = $value[$lv];
                    $s['addtime'] = time();
                    $integral->insert($s);
                }
                $b['sid'] = $value['id'];
                $b['uid'] = $uid;
                $b['addtime'] = time();
                $b['type2'] = 0;
                switch ($lv) {
                    case 'lv1':
                        $str = '注册增加';
                        $b['type2'] = 1;
                        break;
                    case 'lv2':
                        $str = '一级推荐增加';
                        $b['type2'] = 4;
                        $b['payee'] = $userid;
                        break;
                    case 'lv3':
                        $str = '二级推荐增加';
                        $b['type2'] = 4;
                        $b['payee'] = $userid;
                        break;      
                    
                    default:
                        break;
                }
                $b['content'] = $str;
                // $b['tpe'] = 0;
                $b['price'] = $value[$lv];
                if($b){
                    db::name('tpbill') ->insert($b);
                }
            }
        }
    }

    //登录
    public function login(){
	    $version = strtolower(get_input_data('version'));

        if($version == 'v2'){
            $phone = get_input_data('phone');
            if(!preg_match("/^1\d{10}$/",$phone)){
                $this->data['msg'] = '手机格式不正确';
                return json($this->data);
            }

            $client_type = get_input_data('client_type');//验证码类型
            $arr = ['geetest_challenge'=>get_input_data('geetest_challenge'),'geetest_validate'=>get_input_data('geetest_validate'),'geetest_seccode'=>get_input_data('geetest_seccode')];
            $flag = $this->geetest_api2($arr,['user_id'=>$phone,'client_type'=>$client_type,'ip_address'=>getIp()]);

            if(!$flag){
                $this->data['msg'] = '验证失败';
                return json($this->data);
            }

            $password = get_input_data('password');
            $arr = array(
                'username' => $phone,
                'password' => $password,
                'time'     => time(),
            );
            $res = json_encode($arr);
            $array = base64_encode($res);
            $array = authcode($array, 'ENCODE');

            $user = db::name('tpuser')->where('phone='.$phone)->find();
            if(isset($user) && $user['state'] == 0){
                $this->data['msg'] = '帐号已封禁,请联系客服';
                return json($this->data);
            }

            if($user['password']==MD5($password)){
                if(!$user['wetoken_addr']){
                    $wetoken_addr=get_wetoken_url($user['id']);
                    $status=Db::name('tpuser')->update(['wetoken_addr'=>$wetoken_addr,'id'=>$user['id']]);
                    if($status){
                        $user['wetoken_addr']=$wetoken_addr;
                    }
                }
                unset($user['password']);

                session('user',$user);
                db::name('tpuser')->where('id',$user['id'])->update(['last_login_ip'=>getIp(),'last_login_time'=>time()]);
                $this->data['status'] = 1;
                $this->data['msg'] = '登录成功';
                $this->data['data'] = $user;
                $this->data['token'] = urlencode($array);
                return json($this->data);
            }

            $this->data['msg'] = '帐号或密码错误';
            return json($this->data);
        }else if($version == 'v3'){
            $phone = get_input_data('phone');
            if(!preg_match("/^1\d{10}$/",$phone)){
                $this->data['msg'] = '手机格式不正确';
                return json($this->data);
            }

            $flag = $this->afs_api();

            if(!$flag){
                $this->data['msg'] = '验证失败';
                return json($this->data);
            }

            $password = get_input_data('password');
            $arr = array(
                'username' => $phone,
                'password' => $password,
                'time'     => time(),
            );
            $res = json_encode($arr);
            $array = base64_encode($res);
            $array = authcode($array, 'ENCODE');

            $user = db::name('tpuser')->where('phone='.$phone)->find();
            if(isset($user) && $user['state'] == 0){
                $this->data['msg'] = '帐号已封禁,请联系客服';
                return json($this->data);
            }

            if($user['password']==MD5($password)){
                if(!$user['wetoken_addr']){
                    $wetoken_addr=get_wetoken_url($user['id']);
                    $status=Db::name('tpuser')->update(['wetoken_addr'=>$wetoken_addr,'id'=>$user['id']]);
                    if($status){
                        $user['wetoken_addr']=$wetoken_addr;
                    }
                }
                unset($user['password']);

                session('user',$user);
                db::name('tpuser')->where('id',$user['id'])->update(['last_login_ip'=>getIp(),'last_login_time'=>time()]);
                $this->data['status'] = 1;
                $this->data['msg'] = '登录成功';
                $this->data['data'] = $user;
                $this->data['token'] = urlencode($array);
                return json($this->data);
            }

            $this->data['msg'] = '帐号或密码错误';
            return json($this->data);

        }else{
            $phone = get_input_data('phone');
            if(!preg_match("/^1\d{10}$/",$phone)){
                $this->data['msg'] = '手机格式不正确';
                return json($this->data);
            }

            $verify = get_input_data('verify');
            $type = get_input_data('type','');

//            //图像验证码验证
            $verify = get_input_data('verify');
            $type = get_input_data('type','');

            if(!$verify){
                $this->data['msg'] = '验证码为空';
                return json($this->data);
            }

            //验证码验证
            $c = $this->check_verify($verify,$type);

            if(!$c){
                $this->data['msg'] = '验证码错误';
                return json($this->data);
            }

            $password = get_input_data('password');
            $arr = array(
                'username' => $phone,
                'password' => $password,
                'time'     => time(),
            );
            $res = json_encode($arr);
            $array = base64_encode($res);
            $array = authcode($array, 'ENCODE');

            $user = db::name('tpuser')->where('phone='.$phone)->find();
            if($user['password']==MD5($password)){
                if(!$user['wetoken_addr']){
                    $address=get_wetoken_url($user['id']);
                   $status=Db::name('tpuser')->update(['wetoken_addr'=>$address,'id'=>$user['id']]);
                   if($status){
                       $user['wetoken_addr']=$address;
                   }
                }
                unset($user['password']);
                session('user',$user);
                $this->data['status'] = 1;
                $this->data['msg'] = '登录成功';
                $this->data['data'] = $user;
                $this->data['token'] = urlencode($array);
                return json($this->data);
            }

            $this->data['msg'] = '帐号或密码错误';
            return json($this->data);
        }

    }

    public function out(){
        session(NULL);
        $this->data['status'] = 1;
        $this->data['msg'] = '退出成功';
        return json($this->data);        
    }

    //找回密码
    public function forget(){
        $phone = get_input_data('phone');
        if(!preg_match("/^1\d{10}$/",$phone)){
            $this->data['msg'] = '手机格式不正确';
            return json($this->data);
        }

    	$check = db::name('tpuser')->field('id,phone')->where('phone='.$phone)->find();
    	if(!$check){
            $this->data['msg'] = '手机号未注册';
            return json($this->data); 
    	}
      
        $code = get_input_data('code');
        $password = get_input_data('password');

        if(!check_code($phone,$code)){
            $this->data['msg'] = '验证码错误';
            return json($this->data);  
        }
        $result = db::name('tpuser')->where('phone='.$phone)->setField('password',MD5($password));
        if($result){
            $this->data['status'] = 1;
            $this->data['msg'] = '修改成功';
            return json($this->data);
        } 
        $this->data['msg'] = '不能和原密码一致';
        return json($this->data);               
    }


    //获取个人二维码

    public function getQRcode($uid=null,$size=200){

        if(!$uid){
            $result['code'] = 0;
            $result['msg'] = 'UID为空';
            return json($result);
        }

        $url = 'http://www.wetoken.vip/wetoken/reg.html?uid='.$uid;
        // 引入 extend/qrcode.php
        Loader::import('phpqrcode', EXTEND_PATH);
        \QRcode::png($url,'./uploads/share/tmp/qr'.$uid.'.jpg','H',$size/43,1);

    }



    //分享

	public function share(){

		$uid = get_input_data('uid');
		$sid = get_input_data('sid',-1);

		if(!$uid){
			$result['status'] = 0;
			$result['code'] = 400;
			$result['msg'] = 'MISS_PARAM';
			return json($result);
		}

// dump(!is_file('./uploads/share/tmp/'.$uid.'.jpg'));die;

		//检测是否生成过
		if(!is_file('./uploads/share/tmp/'.$sid.'_'.$uid.'.jpg')){
                $model = Db::name('share')->where(['status'=>1,'sid'=>$sid])->order('id desc')->limit(1)->select();

                if(!$model){
                    $result['status'] = 0;
                    $result['code'] = 500;
                    $result['msg'] = 'SERVER_ERROR';
                    return json($result);
                }



                //生成图片
                $pic = $model[0]['pic'];

                $image = Image::open('./uploads/share/'.$pic);
                $code = json_decode($model[0]['code'],true);

                $user = Db::name('tpuser')->where(['id'=>$uid])->find();

                //设置头像

                    //下载头像
                    $tx_tmp = './uploads/share/tmp/tx_'.$uid.'.png';
                    if(is_file($user['pic'])){
                        $dump = file_get_contents($user['pic']);
                        file_put_contents($tx_tmp, $dump);
                    }else{
                        $tx_tmp = './uploads/share/yyy.png';
                    }


                // 调整大小并保存为thumb.png
                $thumb = \think\Image::open($tx_tmp);
                $tx = json_decode($model[0]['text3'],true);
                $thumb->thumb($tx['size'], $tx['size'])->save('./uploads/share/thumb.png');
                $image->water('./uploads/share/thumb.png',array($tx['left'],$tx['top']),100)->water('uploads/share/'.$model[0]['pic']);

                for ($i=1; $i<6; $i++) {
                    $data[$i] = json_decode($model[0]['text'.$i],true);
                    if($i==1){
                        $data[1]['text'] = $uid;
                    }



                    if(!empty($data[$i]['text'])){

                        $image->text($data[$i]['text'],'static/admin/fonts/msyh.ttf',$data[$i]['size'],'#'.$data[$i]['color'],1,array($data[$i]['left'],$data[$i]['top']));

                    }

                }



                //设置二维码
                $this->getQRcode($uid,$code['size']);
                $image->water('./uploads/share/tmp/qr'.$uid.'.jpg',array($code['left'],$code['top']),100);
                $image->save('./uploads/share/tmp/'.$sid.'_'.$uid.'.jpg');
                

		}

		echo '<style>body{margin:0px;}img{width:100%}</style><img src="/uploads/share/tmp/'.$sid.'_'.$uid.'.jpg" />';

	}

    //钱包关于我们
    public function about(){
        $content = db::name('news')->where('id=65')->field('content')->find();
        if ($content) {
            $this->data['status'] = 1;
            $this->data['content'] = $content['content'];
            $this->data['msg'] = '获取成功';
        }else{
            $this->data['msg'] = '获取失败';

        }
        return json($this->data); 
    }

    //钱包帮助信息
    public function help(){
        $content = db::name('news')->where('id=64')->field('content')->find();
        
        if ($content) {
            $this->data['status'] = 1;
            $this->data['content'] = $content['content'];
            $this->data['msg'] = '获取成功';
        }else{
            $this->data['msg'] = '获取失败';

        }  
        return json($this->data); 

    }

    //钱包升级
    public function upgrade(){
        $system = get_input_data('system');
        if ($system == '') {
            $this->data['msg'] = '缺少参数'; 
            return json($this->data);
        }
        $type = ['android'=>0,'iphone'=>1];
        $info = Db::name('upApp')->where(['type'=>$type[$system]])->order('id desc')->find();

        if ($info) {

            $this->data['status'] = 1;
            $this->data['content'] = $info;
            $this->data['msg'] = '获取成功';
            return json($this->data); 

        }
        $this->data['msg'] = '获取失败'; 
        return json($this->data);         
    }

    //用户协议
    public function agreement(){
        $language = strtoupper(get_input_data('language'));
        if ($language == '') {
            $this->data['msg'] = '缺少参数'; 
            return json($this->data);
        }
        if ($language == 'EN') {
            $data['id'] = 67;
        }elseif($language == 'CN'){
            $data['id'] = 66;
        }else{
            $this->data['msg'] = '未知语言';
            return json($this->data); 
        }
        $content = db::name('news')->where($data)->field('content')->find();
        if ($content) {
            $this->data['status'] = 1;
            $this->data['content'] = $content['content'];
            $this->data['msg'] = '获取成功';
        }else{
            $this->data['msg'] = '获取失败';

        }  
        return json($this->data); 

    }
    // 获取支持代币
    public function getCoinList(){
        $result = db::name('tpcoinlist')->field('id,name,address,pic')->where('status=1 and is_default_show=0')->order('c_order desc')->limit(20)->select();
        if(session('user.id')){
            $r = db::name('usercoin')->where('uid='.session('user.id'))->find();
            $list = json_decode($r['coinlist']);
            if($list){

                foreach ($list as $key => $value) {
                    foreach ($result as $k =>$v) {
 
                        if($v['id'] == $value){
                            $flag = $k;
                        }
                        // $result[$k]['show'] = 0;
                    }
                    if(isset($flag)){                    
                        $result[$flag]['show'] = 1;
                    }else{
                        $res = db::name('tpcoinlist')->field('id,name,address,pic')->where('id='.$value)->find();
                        $res['show'] = 1; 
                        $result[] = $res;
                    }

                }               
            }
        }
        
        foreach ($result as $key => $value) {
            if(!isset($value['show'])){
                $result[$key]['show'] = 0;
            }
        }

        if($result){
            $this->data['status'] = 1;
            $this->data['data'] = $result;
            $this->data['msg'] = '获取成功';
            return json($this->data);
        }

        $this->data['msg'] = '获取失败';
        return json($this->data);
    }

    public function push(){

        $data = db::name('news')->field('ims_news.id,ims_news.title,ims_news.content as intro,ims_news.addtime,ims_news_type.title as type')
            ->join('ims_news_type', 'ims_news_type.id=ims_news.identify', 'LEFT')
            ->where('ims_news.status=1 AND ims_news.recommend=1')
            ->select();
            // var_dump($data);exit;
        if($data){
            $d['recommend'] = 0;
            foreach ($data as $key => $value) {
                db::name('news')->where('id='.$value['id'])->update($d);
            }
            $this->data['status'] = 1;
            $this->data['data'] = $data;
            $this->data['msg'] = '获取成功';
            return json($this->data);
        }
        $this->data['status'] = 1;
        $this->data['msg'] = '暂无推送内容';
        return json($this->data);    
    }

    public function getNews(){
        $nid = intval(get_input_data('nid'));
        if($nid == 0){
           $this->data['msg'] = '未知ID';
           return json($this->data);         
        }
      $news = db::name('news')->field('id,content,title,addtime')->where('id='.$nid)->find();
      
      if($news){
            $this->data['status'] = 1;
            $this->data['data'] = $news;
            $this->data['msg'] = '获取成功';
            return json($this->data);       
      }
      $this->data['status'] = 0;
      $this->data['msg'] = '该文章已删除';
      return json($this->data);       
    }

  	public function getAD(){
      $res = db::name('tpadpic')->where('status=1')->order('sort desc')->select();
      if($res){
            $this->data['status'] = 1;
            $this->data['data'] = $res;
            $this->data['msg'] = '获取成功';
            return json($this->data);          
      }else{
      $this->data['status'] = 0;
      $this->data['msg'] = '暂无数据';
      return json($this->data);          
      }
    }  


        //留言
    public function message(){
       $data['name'] = get_input_data('name');
       $data['phone'] = get_input_data('phone');
       $data['content'] = get_input_data('content');
       $data['type'] = get_input_data('type/d',1);
        $data['status'] = 1;
        $data['addtime'] = time();
       if(!$data['name']){
          $this->data['msg'] = '姓名不能为空';
          return json($this->data);      
       }
        if(!$data['phone']){
          $this->data['msg'] = '手机号不能为空';
          return json($this->data);      
       }
        if(!preg_match("/^1\d{10}$/",$data['phone'])){
            $this->data['msg'] = '手机号格式不正确';
            return json($this->data);
        }      
       if(!$data['content']){
          $this->data['msg'] = '内容';
          return json($this->data);      
       }
      
       $res = db::name('message')->insert($data);
      if($res){
            $this->data['status'] = 1;
            $this->data['msg'] = '保存成功';  
           return json($this->data);       
      }else{
          $this->data['msg'] = '保存失败';
          return json($this->data);            
      }
         
    }

        //验证码 1111111111111
    public function verify($type = ''){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $captcha = new \think\captcha\Captcha($config);
		return $captcha->entry($type);
    }

    //检测验证码
    private function check_verify($code, $id = ''){
        $captcha = new \think\captcha\Captcha();
        return $captcha->check($code, $id);
    }

    //定时任务
    public function timed_task()
    {
        $soretype = db::name('tpsoretype')->select();
        foreach ($soretype as $k => $v) {
            if ($v['num']) {
                db::name('tpintegral')->where('sid='.$v['id'])->update(['usable' => ['exp', 'integral *'.$v['num']/100]]);
            }
        }
    }



    public function tpuser(){
        $api = new apiModel();
        $p = get_input_data('p');
        $row = get_input_data('row', 5000);
        $member = $api->onedb('lb_member', $p, $row);
        if (!$member) {
            echo '暂无数据';
        }
        foreach($member as $k => $v){
        	$arr = array(
            	'id' => $v['id'],
              	'username' => $v['nickname'],
              	'pic' => $v['pic'],
              	'password' => $v['password'],
              	'phone' => $v['phone'],
              	'rtid' => $v['rtid'],
              	'rtid2' => $v['rtid2'],
            );

          db::name('tpuser')->insert($arr);
        }
    }
  

  
  	public function tpintegral(){
        $api = new apiModel();
        $p = get_input_data('p');
        $row = get_input_data('row', 5000);
        $member = $api->onedb('lb_member', $p, $row);
        if (!$member) {
            echo '暂无数据';
        }
        foreach($member as $k => $v){
        	$arr = array(
            	'uid' => $v['id'],
              	'sid' => 7,
              	'addtime' => time(),
              	'integral' => $v['jifen'],
            );
          db::name('tpintegral')->insert($arr);
        }
    }
  
    public function tparticle(){
        $api = new apiModel();
        
        $member = $api->onedb2('lb_post');
        if (!$member) {
            echo '暂无数据';
        }
        //1
        foreach($member as $k => $v){
            $arr = array(
                'id' => $v['id'],
                'identify' => $v['cid'],
                'title' => $v['title'],
                'intro' => $v['sub_title'],
                'content' => $v['content'],
                'addtime' => $v['addtime'],
                'status' => $v['status'],
            );
            if (!empty($v['file'])) {
                $a = @unserialize($v['file']);
                if (isset($a[0])) {
                    
                    $arr['pic'] = $a[0];
                // dump($a);

                }
            }
            
          db::name('news')->insert($arr);
        }
    }

    /**
     * 单边上扬K线图
     * @return [type] [description]
     */
    public function upMarket()
    {
        $sid = get_input_data('sid/d');

        if(!$sid){
            $this->data['msg'] = '币种不能为空';
            return json($this->data);
        }

        $coin =Db::name('marketConfig')->where(['sid'=>$sid])->value('coin');
        $row = get_input_data('row/d',7);
        if(!$coin){
          $this->data['msg'] = '币种不能为空';
          return json($this->data);  
        }
        $data = Db::name('market')->where(['coin'=>$coin])->order('create_time desc')->limit($row)->select();

        if($data){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';  
            $this->data['data'] = $data;  
            return json($this->data);       
        }else{
            $this->data['msg'] = '暂无数据';
            return json($this->data);            
        } 
    }

    /**
     * 多图片上传
     * @return [type] [description]
     */
    public function uploadsPic()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $pics = request()->file();
        $tmp = '';
        foreach ($pics as $key => $value) {
            $info = $value->move(ROOT_PATH . 'public' . DS . 'uploads');
            $flag = true;//成功标志
            if($info){
                $pic = $info->getFilename();
                $file = ROOT_PATH . 'public' . DS . 'uploads'.DS.$info->getSaveName();

                $ossClient  = new \OSS\OssClient(config('OSS_KEY'), config('OSS_SECRET'), config('OSS_ENDPOINT'));
                $bucket     = config('OSS_BUCKET');

                $pic = date('ymd').'/'.$pic;
                try {
                    // $ossClient->putObject(config('OSS_BUCKET'), $pic, $content);
                    $ossClient->uploadFile($bucket, $pic, $file);
                    $tmp[$key] = 'http://'.config('OSS_BUCKET').'.img'. substr(config('OSS_ENDPOINT'), 3).'/'. $pic;
                    //删除临时文件
                    unlink(ROOT_PATH . 'public' . DS . 'uploads'.DS.$info->getSaveName());
                } catch (\OSS\Core\OssException $e) {
                    $tmp[$key] = '上传失败';
                    $flag = false;
                }
            }else{
                $tmp[$key] = '图片格式不支持或超过大小';
                $flag = false;
            }



        }

        if($flag){
            $this->data['status'] = 1;
            $this->data['msg'] = '上传成功';
            $this->data['data'] = $tmp;
            return json($this->data);
        }else{
            $this->data['msg'] = '上传失败';
            $this->data['data'] = $tmp;
            return json($this->data);
        }

    }

    /**
     * 单图片上传
     * @return [type] [description]
     */
    public function uploads()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            if($info){
                $pic = $info->getFilename();
                $file = ROOT_PATH . 'public' . DS . 'uploads'.DS.$info->getSaveName();

                $ossClient  = new \OSS\OssClient(config('OSS_KEY'), config('OSS_SECRET'), config('OSS_ENDPOINT'));
                $bucket     = config('OSS_BUCKET');

                $pic = date('ymd').'/'.$pic;
                try {
                    $ossClient->uploadFile($bucket, $pic, $file);
                    $url = 'http://'.config('OSS_BUCKET').'.img'. substr(config('OSS_ENDPOINT'), 3).'/'. $pic;
                    //删除临时文件
                    unlink(ROOT_PATH . 'public' . DS . 'uploads'.DS.$info->getSaveName());

                    $this->data['status'] = 1;
                    $this->data['msg'] = '上传成功';
                    $this->data['data'] = $url;
                    return json($this->data);

                } catch (\OSS\Core\OssException $e){
                    $this->data['msg'] = '上传失败';
                    $this->data['data'] = $e;
                    return json($this->data);
                }
            }else{
                $this->data['msg'] = '图片格式不支持或超过大小';
                return json($this->data);
            }

        }

        $this->data['msg'] = '未找到资源';
        return json($this->data);

    }

    public function validates(){
      	$key = get_input_data('type');
        getValidat($key);die;
    }

    /**
     * 获取手续费比例
     */
    public function getFee(){
        $sid = get_input_data('sid/d');
        if(!$sid){
            $this->data['msg'] = '币种ID为空';
            return json($this->data);
        }
        $fee = Db::name('tpsoretype')->where(['id'=>$sid,'status'=>1])->find();
        if(!$fee){
            $this->data['msg'] = '币种不存在';
        }else{
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $fee;
        }
        return json($this->data);
    }

    /**
     * 定时更新订单
     */
    public function upc2c(){
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期

        //过期订单
        $list1 = Db::name('tpc2c')->where(['status'=>1])->where('enddate_time','<=',time())->select();

        if($list1){
            $config = Db::name('config')->where(['id'=>2])->value('content');
            $config = json_decode($config,true);

            if($config['status']){
                //48小时回购
                $data['payid'] = 100;
                $data['pay_phone'] = 1888888888888;
                $data['pay_name'] = 'wetoken管理员';
                $data['update_time'] = time()+86400;
                $data['action_time'] = time();
                $data['status'] = 2;

                foreach ($list1 as $key => $value){
                    Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>100,'time'=>time(),'content'=>'已接单']);
                }

                Db::name('tpc2c')->where(['status'=>1])->where('enddate_time','<=',time())->update($data);
            }else{
                foreach($list1 as $key=>$value){
                    Db::name('tpc2c')->where(['id'=>$value['id']])->update(['status'=>0]);
                    //积分解冻
                    Db::name('tpintegral')->where(['uid'=>$value['uid'],'sid'=>$value['sid']])->update(['frozen'=>['exp','frozen-'.($value['num']+$value['fee'])],'integral'=>['exp','integral+'.($value['num']+$value['fee'])]]);
                    //糖果流水
                    Db::name('tpbill')->insert([
                        'sid'=>$value['sid'],
                        'uid'=>$value['uid'],
                        'addtime'=>time(),
                        'content'=>'c2c挂单冻结释放',
                        'type'=>0,
                        'price'=>$value['num']+$value['fee'],
                        'type2'=>5,
                        'payee'=>0,
                    ]);

                    Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'订单已到期']);
                    echo $value['id']."已到期.\n";
                }
            }

        }

        //未及时付款
        $list2 = Db::name('tpc2c')->where(['status'=>2,'update_time' =>['<',time()]])->select();
        if($list2){
            foreach($list2 as $key=>$value){
                if($value['payid'] == 100){
                    continue;
                }
                Db::name('tpc2c')->where(['id'=>$value['id']])->update(['status'=>0]);
                //积分解冻
                Db::name('tpintegral')->where(['uid'=>$value['uid'],'sid'=>$value['sid']])->update(['frozen'=>['exp','frozen-'.($value['num']+$value['fee'])],'integral'=>['exp','integral+'.($value['num']+$value['fee'])]]);
                Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'订单失败']);
                Db::name('blackList')->insert(['uid'=>$value['payid'],'oid'=>$value['id'],'time'=>time()]);
                //糖果流水
                Db::name('tpbill')->insert([
                    'sid'=>$value['sid'],
                    'uid'=>$value['uid'],
                    'addtime'=>time(),
                    'content'=>'c2c挂单冻结释放',
                    'type'=>0,
                    'price'=>$value['num']+$value['fee'],
                    'type2'=>5,
                    'payee'=>0,
                ]);
                echo $value['id'].'订单-用户'.$value['pay_name']."未及时付款.\n";
            }
        }

        //未及时发币
        $list3 = Db::name('tpc2c')->where(['status'=>3,'update_time' =>['<',time()]])->select();
        if($list3){
            foreach($list3 as $key=>$value){
                Db::name('tpc2c')->where(['id'=>$value['id'],'update_time' =>['<',time()]])->update(['status'=>5]);
                //系统发币
                Db::name('tpintegral')->where(['uid'=>$value['uid'],'sid'=>$value['sid']])->update(['frozen'=>['exp','frozen-'.($value['num']+$value['fee'])]]);

                $id = Db::name('tpintegral') ->where(['uid'=>$value['payid'],'sid'=>$value['sid']])->value('id');
                if($id){
                    Db::name('tpintegral')->where(['id'=>$id])->update(['integral'=>['exp','integral+'.$value['num']]]);
                }else{
                    Db::name('tpintegral')->insert(['integral'=>['exp','integral+'.$value['num']],'uid'=>$value['payid'],'sid'=>$value['sid'],'addtime'=>time()]);
                }
                //币流水
                Db::name('tpbill')->insert([
                    'sid'=>$value['sid'],
                    'uid'=>$value['payid'],
                    'addtime'=>time(),
                    'content'=>'购买币',
                    'type'=>0,
                    'price'=>$value['num'],
                    'type2'=>5,
                    'payee'=>$value['uid'],
                    'ordersn'=>$value['cid'],

                ]);
                Db::name('tpbill')->insert([
                    'sid'=>$value['sid'],
                    'uid'=>$value['uid'],
                    'addtime'=>time(),
                    'content'=>'出售币',
                    'type'=>1,
                    'price'=>$value['num'],
                    'type2'=>5,
                    'payee'=>$value['payid'],
                    'ordersn'=>$value['cid'],
                    'proc' =>$value['fee']

                ]);
                //c2c流水
                Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'系统发币']);
                Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'系统自动结束']);
                echo $value['id'].'订单-用户'.$value['pay_name']."未及时付款.\n";
/*
                //积分解冻
                Db::name('tpintegral')->where(['uid'=>$value['uid'],'sid'=>$value['sid']])->update(['frozen'=>['exp','frozen-'.$value['num']],'integral'=>['exp','integral+'.$value['num']]]);
                Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'系统自动结束']);
                Db::name('blackList')->insert(['uid'=>$value['uid'],'oid'=>$value['id'],'time'=>time()]);
                echo $value['id'].'订单-用户'.$value['pay_name']."未及时付款.\n";
*/

            }
        }

        //自动结束
        $list4 = Db::name('tpc2c')->where(['status'=>3,'update_time' =>['<',time()]])->select();
        if($list4){
            foreach($list4 as $key=>$value){
                Db::name('tpc2c')->where(['id'=>$value['id']])->update(['status'=>0]);
                Db::name('tpc2cBill')->insert(['cid'=>$value['id'],'uid'=>0,'time'=>time(),'content'=>'自动结束']);
                echo $value['id']."系统结束.\n";
            }
        }

         //关小黑屋
        $sql = 'SELECT uid,count(*) AS num FROM ims_black_list WHERE time>'.(time()-604800).' AND status=1 GROUP BY uid ';
        $list5 = Db::query($sql);
        foreach ($list5 as $key => $value){
            if($value['num']>=3){ //3条关小黑屋
                Db::name('tpuser')->where(['id'=>$value['uid']])->update(['ispay'=>0]);
            }
        }

        echo "更新结束\n";


    }

    /*
    *获取兑换记录
    */
    public function getOrder(){
        $k = '1';
        $token = get_input_data('token');
        if($k != $token){
            $this->data['msg'] = '未知令牌';
            return json($this->data);
        }
        $p = get_input_data('p',1);
        $row = get_input_data('row',20);

        $res = Db::name('tpexchange')
            ->alias('e')
            ->join('ims_tpcoinlist c', 'c.sid=e.sid')
            ->field('e.id,e.address,e.price,c.address as contractAddr,c.decimals,c.symbol')
            ->where('e.stutas=-1')
            ->order('e.id')->limit(($p-1)*$row,$row)
            ->select();

        if($res){
            $this->data['msg'] = '获取成功';
            $this->data['status'] = 1;
            $this->data['data'] = $res;
            return json($this->data);
        }

        $this->data['msg'] = '获取失败';
        return json($this->data);
    }

    /*
    *修改兌换记录状态
    */
    public function setOrder(){
        $k = '1';
        $id = intval(get_input_data('id'));
        $data['stutas'] = intval(get_input_data('status'));
        $data['content'] = get_input_data('content');
        $token = get_input_data('token');
        if($k != $token){
            $this->data['msg'] = '未知令牌';
            return json($this->data);
        }
        if(!$id){
            $this->data['msg'] = '未知ID';
            return json($this->data);
        }
        if($data['stutas']!=1 && $data['stutas']!=2){
            $this->data['msg'] = '未知状态';
            return json($this->data);         
        }
        $r = Db::name('tpexchange')->where('id='.$id.' and stutas=-1')->find();
        if(!$r){
            $this->data['status'] = 1;
            $this->data['msg'] = '重复提交';
            return json($this->data);             
        }
        $res = Db::name('tpexchange')->where('id='.$id)->update($data);
        if($res){
            $this->data['status'] = 1;
            $this->data['msg'] = '修改成功';
            return json($this->data); 
        }
        $this->data['msg'] = '修改失败';
        return json($this->data); 
    }

    public function geetest_api(){
        $phone = get_input_data('phone');
        $client_type = get_input_data('client_type');
        $ip = getIp();

        import('geetest.config', EXTEND_PATH);
        import('geetest.geetestlib', EXTEND_PATH);

        $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);

        $data = array(
            "user_id" => $phone, # 网站用户id
            "client_type" => $client_type, #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);

        cache('xw_'.$phone,['gtserver'=>$status,'user_id'=>$data['user_id']],300);

        return $GtSdk->get_response_str();
    }

    //修复注册异常账号
    public function user_rtid()
    {
        $id = get_input_data('id');
        $user = db::name('tpuser')->where('id='.$id)->find();
        if($user){

            $rtid = db::name('tpuser')->where('rtid='.$user['id'])->select();
            if ($rtid) {
                foreach ($rtid as $key => $value) {
                    db::name('tpuser')->where('id='.$value['id'])->update(['rtid'=>$user['phone']]);
                }
            }

            $rtid2 = db::name('tpuser')->where('rtid2='.$user['id'])->select();
            if ($rtid2) {
                foreach ($rtid2 as $key => $value) {
                    db::name('tpuser')->where('id='.$value['id'])->update(['rtid2'=>$user['phone']]);
                }
            }

            if ($user['rtid']) {
                $res = db::name('tpuser')->where('id='.$user['rtid'])->find();
                if ($res) {
                    db::name()->where('id='.$user['id'])->update(['rtid'=>$res['phone']]);
                }
            }
            if ($user['rtid2']) {

                $res2 = db::name('tpuser')->where('id='.$user['rtid2'])->find();
                if ($res2) {
                    db::name()->where('id='.$user['id'])->update(['rtid2'=>$res2['phone']]);
                }
            }

            $this->data['status'] = 1;

            $this->data['msg'] = '修复完成';

            return json($this->data);
        }

        $this->data['msg'] = '输入正确的id';
        return json($this->data);
        
    }

    public function getMsgInfo(){
        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);
        if(isset($arr['message'])){
            $arr['message']['tip'] = htmlspecialchars_decode($arr['message']['tip']);
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $arr['message'];
            return json($this->data);
        }

        $this->data['msg'] = '暂无配置';
        return json($this->data);
    }

    //矿机挖币
    public function miner(){
        set_time_limit(0);
/*
        //去掉过期的矿机
        Db::name('shop_orderform')->where(['zt' => 1])->where('uptime','<',time())->update(['zt'=>2]);

        $shouyi = 0.001421875;  //每小时收益
        $price   = db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $chanbi = $shouyi/$price;  //每M全网出币量
        $miner = Db::name('miner')->where(['sid'=>7])->find();//配置参数
echo '行情'.$price.'<br/>';
        $shop = db::name('shop_orderform')->field('goodsid,uid,sum(kjsl) as kjsl')->group('uid')->where(['zt' => 1])->select();

        foreach ($shop as $key => $value) {

            $res = floor($chanbi*$value['kjsl']*10000)/10000;
            $integral = db::name('tpintegral')->where(['uid' => $value['uid'],'sid'=>7])->find();

            db::name('tpintegral')->where(['uid' => $value['uid'], 'sid' => 7])->update(['money' => $integral['money']+$res, 'dug_money' => $integral['dug_money']+$res, 'integral' => $integral['integral']+$res]);

            echo $value['uid'].'产币'.$res.'个<br/>';
            $arr = array(
                'kjsl' => $value['kjsl'],
                'addtime' => time(),
                'uid' => $value['uid'],
                'money' => $res,
            );
            db::name('shop_log')->insert($arr);
        }
        */
        //去掉过期的矿机
        Db::name('shop_orderform')->where(['zt' => 1])->where('uptime','<',time())->update(['zt'=>2]);
        $price  =  db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $miner = Db::name('miner')->where(['sid'=>7])->find();//配置参数
        $kjsl      = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl'); //全部矿机算力
        $quanwang = $kjsl+$miner['force']+$price*100;
        $chanbi = ($kjsl*2.5+2500000)/1846/$price;  //每小时产币

//        echo '行情'.$price.'<br/>';
//        echo '全网算力'.$quanwang.'<br/>';
//        echo '用户总算力'.$kjsl.'<br/>';
//        echo '每小时产币'.$chanbi.'<br/>';

        $shop = db::name('shop_orderform')->field('goodsid,uid,sum(kjsl) as kjsl')->group('uid')->where(['zt' => 1])->select();

        foreach ($shop as $key => $value) {

            $res = floor($chanbi*$value['kjsl']/$quanwang*10000)/10000;
            $integral = db::name('tpintegral')->where(['uid' => $value['uid'],'sid'=>7])->find();

            db::name('tpintegral')->where(['uid' => $value['uid'], 'sid' => 7])->update(['money' => $integral['money']+$res, 'dug_money' => $integral['dug_money']+$res, 'integral' => $integral['integral']+$res]);

            //echo $value['uid'].'算力'.$value['kjsl'].'产币'.$res.'个<br/>';
            $arr = array(
                'kjsl' => $value['kjsl'],
                'addtime' => time(),
                'uid' => $value['uid'],
                'money' => $res,
            );
            db::name('shop_log')->insert($arr);
            usleep(500);
        }
        echo 1;
    }

    //算力
    public function calculation_force(){
        $info       = db::name('miner')->find();
        $kjsl      = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl');
        $arr = array(
                        'force'  => $info['initial']+$kjsl,
                        'degree' => $info['degree']
                    );
        if($arr){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $arr;
            return json($this->data);
        }else{
            $this->data['msg'] = '暂无数据';
            return json($this->data);
        }
        exit;
       
    }

    //结算
    public function settlement(){
        db::name('tpintegral')->where('sid=7')->update(['dug_money' => 0]);
        exit;
        $intrgral = db::name('tpintegral')->where('sid=7')->select();
        $kjsl   = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl');

        $price   = db::name('market')->where(['coin' => 'btjz'])->value('price');
        $info       = db::name('miner')->where('sid=7')->find();
        $miner_log  = db::name('miner_log')->order('id desc')->find();
        if ($miner_log) {
            $money_num  = 145.6/$price*4;
            $bili = $money_num/$miner_log['money_num'];//币数除于昨日币数=比列
            $force = $miner_log['calculation_force']*$bili;//昨日算力乘于比例=今日算力
            $data = array(
                        'calculation_force' => $force,
                        'money_num' => $money_num,
                        'addtime' => time()
                    );
            db::name('miner_log')->insert($data);
        }else{

            $money_num  = 145.6/$info['price'];
            $data = array(
                        'calculation_force' => $info['force']+$kjsl,
                        'money_num' => $money_num,
                        'addtime' => time()
                    );
            db::name('miner_log')->insert($data);
        }
        // $array = array(
        //         'money_num' => $money_num,
        //         'calculation_force' => 
        //     );
        // db::name('miner_log')->insert();
    }

    public function getSweetStatus(){
        $c = file_get_contents(__DIR__.'/../../config.json');
        $arr = json_decode($c,true);

        $this->data['status'] = 1;
        $this->data['msg'] = '获取成功';
        $this->data['data'] = $arr['sweet'];
        return json($this->data);
    }

    /**
     * 获取等级说明
     */
    public function getUserLevel(){
        $sid = get_input_data('sid',7);
        $level = Db::name('tpsoreLevel')->field('name,min,interest')->where(['sid'=>$sid])->select();
        $data['level'] = $level;

        $this->data['status'] = 1;
        $this->data['msg'] = '获取成功';
        $this->data['data'] = $data;
        return json($this->data);
    }


    /**
     * 短信验证码
     * @return \think\response\Json
     */
    public function checkCode(){
        $phone = get_input_data('phone');
        $code = get_input_data('code');

        if(empty($phone)){
            $this->data['msg'] = '手机号为空';
            return json($this->data);
        }
        if(empty($code)){
            $this->data['msg'] = '短信验证码为空';
            return json($this->data);
        }


        if(!check_code($phone,$code)){
            $this->data['msg'] = '短信验证码错误';
            return json($this->data);

        }else{
            $this->data['status'] = 1;
            $this->data['msg'] = '验证通过';
            return json($this->data);
        }
    }

    /**
     * 用户搜索
     */
    public function search_user(){
        $id = get_input_data('id');
        if(!$id){
            $this->data['msg'] = '关键词为空';
            return json($this->data);
        }

        $data = Db::name('tpuser')->field('id,username,phone,wetoken_addr')->where('id='.$id.' or phone='.$id)->find();

        if($data){
            $this->data['status'] = 1;
            $this->data['msg'] = '查询成功';
            $this->data['data'] = $data;
            return json($this->data);
        }else{
            $this->data['msg'] = '没有找到相关搜索';
            return json($this->data);
        }
    }


    //base64上传图片
    public function base64_img(){
        $image="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASIAAAEiCAYAAABdvt+2AAAgAElEQVR4Xuy9B5hlV3Wm/Z58bq6cujpVB3WUWlJ308oERSQMCAVAAkQQYoi2sQcMAzOD5zcG29hjDGYMxhiTDMaYKEAgwMoSKNBqda7uyunmcHL4n32qW0gCLIG6JAR3P0+pq0q39rl37X2+s/Za3/qWJCPHtMdTagFJkhavJ0VIyEgYyKpPV4/MpReexHXXvIWgZOEcvofueBZfMnDVHKphkpNc/OYUsmoQGiNY6hBKvg8zckBT0TWJrGZTntyPPL2HrD9DKpjDDz3cWCGt5Ik9CJQ+vNwaAnccPfIJl51Oo1rk0r/4AVXPe0rt0b5Y2wJSG4iehk1wDIcgRkJB1TQkxWHD5m4uf94Ozn/OiykdGSdd2UdnNIGvaIRqFl2VMJwSUbOCanYQ6P045iCe2omiRchxgBR6FNI6chhAsURU2osZTeEFDYIgIKeoOIHKQtBJ/qRzCcsLxNWHmA41urqHueDP76UV2E+DUdqXfLIWEA+4OI7RdR3f95PvnymjDURPw0rJsnzsqjFxLKOqEh09Er93+U7eeMVL6S4s574ffp+eYJRccBSXApqZwZQDKB3BDH3cWMcz0rhGL5E5hKNk6TY1gnoVzw3RUgVyikdUOoDcGscNbILQJq20kMOIVqzS1LoYGH4W8/tuoaqayGqBF/79OE7oPg1WaV/yyVpAURTCMCSTyeA4TvL9M2W0gehpWKmfAVFyPkNWInoHJS58/hbe8KLX0JHtoXjoIbTSA2T9cZphD6lUGt1vIZcOYhLixDK1KEbK9RLqgyidG4hbDfKyjOdGeLGKHM+ScorkZYemY9O0HYyoQY46KUOi4USMBd0UFI+GnKXYgpd+eppAeFPt8Yy2gNhjYRQi8bD7/Rv9edpA9DQsz3EgkonRJJlY8tl0coHfe8mzePYZ19JjyMz+9A7yThnNb9Io5Ok3O7GnJ4jrR4l1hyCUcIou2c4BPDNLZHRT6F6H60BUH0NzjqJGNprrYhgqTTnE8z0Mv4kqN1HjGK8VomJSkySsKGZW38ArPnIvTtg+mj0N2+J3+pJtIHoalv9hIIplZELyBYnTdi3n5ddexGmbXoBdnMWfG8NoFlF9i3o6hykVkEMFXbHxW5P45XHiWg1FMdA7+5GNbiK9i0gywVpAsWcw/FkMfx458vDoxVK7sCJY0dMN84dwq4eRcx1UImg6PgvaRl750XtpRW0gehq2xe/0JdtA9DQs/3Eg0iSDKHTo6Fa45LJt3PDGaxnuOJWxffuQW1WiaomUGhMWevG8HIXCEG7QIh2XaY3ehdocR5MifMlEVQoERo6yHZDRVdIiNdY6TCacRPVaxPSTW7MLY+PplO+6D2/hMH7jALGhU48CXLdJ1L2Nl3zwVqrxMye28DQsX/uSS2CBNhAtgVEfb8qHPaLIRJIclq9Wef4LzuBlL7uC+cMRfqPJSatW0Jybw1RllM4BysUQXc1hpmViq4QzvRtK95I3A0JS6FGGpqLhGCkK+R5kx6dhTZKxF0gHVcbHpxlYtRrbS5HvLBAiY9khWqtBINnE8RyjlYD3fHWeA5U2ED3eGrb//4m1QBuITqw9H3c2kWL9GRAZZPMSp+8aYPPWVbz1zX/E/jvHsZotlvcNIQURuqbTkjTsZoSu6sgEzM9MsywXML/7y/SYNrqWQyNPJQqQC3k6st3YFYv0yo0444d48Ac3o8czbDxJI230Y+kp6FqDLQ8hlYvENNDieeZrJW7a2+Tvvj9PGIfExCAdSwE/czLBj7sG7Rf85lmgDURP8ZqoqprwPDzPIwgjzFTEBZeu59WvfjWnbj2f+pEigR9jtXxkySBf6KTieklK9jiINVs2mbDG0ds+Q3P0Doa7c6jZVSjZAqGqM3Fkkq0jw1jpEeLaGD/8/Be58oJV+P4RAn0VVaWXMD2MbBZQUhlCv4niFWksjGI1q1zz//aDomEFLrEUkfCd4mdG9uUpXs725U6QBdpAdI";

        $imageName = "25220_".date("His",time())."_".rand(1111,9999).'.png';

        if (strstr($image,",")){
            $image = explode(',',$image);
            $image = $image[1];
        }

        $path = "uploads/".date("Ymd",time());

        if (!is_dir($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }

        $imageSrc=  $path."/". $imageName;  //图片名字

        $r = file_put_contents(ROOT_PATH ."public/".$imageSrc, base64_decode($image));//返回的是字节数

        if (!$r) {
            return json(['data'=>null,"code"=>1,"msg"=>"图片生成失败"]);
        }else{
            return json(['data'=>1,"code"=>0,"msg"=>"图片生成成功"]);
        }
    }


    //省市县三级联动
    public function getarea(){
        $pid = get_input_data('pid',0);
        $res = Db::name('area')->field('id,area_name,area_code')->where(['area_parent_id'=>$pid])->select();
        if($res){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $res;
        }else{
            $this->data['msg'] = '未找到相关数据';
        }

        return json($this->data);
    }

    //更新日志
    public function up_log(){
        $type = input('type',0);
        $p = input('p',1);
        $row = input('row',10);

        $res['list'] = Db::name('upApp')->where(['type'=>$type])->limit(($p-1)*$row,$row)->order('id desc')->select();
        $res['tatal'] = Db::name('upApp')->where(['type'=>$type])->count();

        $this->data['status'] = 1;
        $this->data['msg'] = '获取成功';
        $this->data['data'] = $res;

        return json($this->data);

    }

    //ET采集
    public function etpost(){
        $data['title'] = input('title');
        $data['content'] = input('content');
        $data['content'] = '<p class="MsoNormal" style="text-indent:22pt;">'.$data['content'].'</p>';
        $data['identify'] = 8;
        $data['mode'] = 1;
        $data['status'] = 1;
        $time = date('Y-m-d',time());
        $data['addtime'] = rand(time(),strtotime($time.' 23:59:59'));
        $key = input('key');

        if($key != 'wetoken.vip'){
            return;
        }

        Db::name('news')->insert($data);

        return '[OK]';
    }

}

