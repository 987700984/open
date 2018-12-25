<?php
/**
 * 生成操作按钮
* @param array $operate 操作按钮数组
*/
function showOperate($operate = [])
{
	if(empty($operate)){
		return '';
	}
	$option = <<<EOT
<div class="btn-group">
    <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        操作 <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
EOT;

	foreach($operate as $key=>$vo){

		$option .= '<li><a href="'.$vo.'">'.$key.'</a></li>';
	}
	$option .= '</ul></div>';

	return $option;
}

/**
 * 将字符解析成数组
 * @param $str
 */
function parseParams($str)
{
	$arrParams = [];
	parse_str(html_entity_decode(urldecode($str)), $arrParams);
	return $arrParams;
}



/**
 * 子孙树 用于菜单整理
 * @param $param
 * @param int $pid
 */
function subTree($param, $pid = 0)
{
	static $res = [];
	foreach($param as $key=>$vo){

		if( $pid == $vo['pid'] ){
			$res[] = $vo;
			subTree($param, $vo['id']);
		}
	}

	return $res;
}

/**
 * 整理菜单
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
	$parent = []; //父类
	$child = [];  //子类
    
    $len=count($param);//6

    for($k=0; $k <= $len; $k++)
    {
        for($j=$len-1;$j>$k;$j--){
            if($param[$j]['sort']<$param[$j-1]['sort']){
                $temp = $param[$j];
                $param[$j] = $param[$j-1];
                $param[$j-1] = $temp;
            }
        }
    }
    

	foreach($param as $key=>$vo){

		if($vo['typeid'] == 0){
			$vo['href'] = '#';
			$parent[] = $vo;
		}else{
			$vo['href'] = url($vo['module_name'] .'/'.$vo['control_name'] .'/'. $vo['action_name']); //跳转地址
			$child[] = $vo;
		}
	}

	foreach($parent as $key=>$vo){
		foreach($child as $k=>$v){

			if($v['typeid'] == $vo['id']){
				$parent[$key]['child'][] = $v;
			}
		}
	}
	unset($child);

	return $parent;
}



/**
 * 解析备份sql文件
 * @param $file
 */
function analysisSql($file)
{
	// sql文件包含的sql语句数组
	$sqls = array ();
	$f = fopen ( $file, "rb" );
	// 创建表缓冲变量
	$create = '';
	while ( ! feof ( $f ) ) {
		// 读取每一行sql
		$line = fgets ( $f );
		// 如果包含空白行，则跳过
		if (trim ( $line ) == '') {
			continue;
		}
		// 如果结尾包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)，
		if (! preg_match ( '/;/', $line, $match ) || preg_match ( '/ENGINE=/', $line, $match )) {
			// 将本次sql语句与创建表sql连接存起来
			$create .= $line;
			// 如果包含了创建表的最后一句
			if (preg_match ( '/ENGINE=/', $create, $match )) {
				// 则将其合并到sql数组
				$sqls [] = $create;
				// 清空当前，准备下一个表的创建
				$create = '';
			}
			// 跳过本次
			continue;
		}

		$sqls [] = $line;
	}
	fclose ( $f );

	return $sqls;
}


/**
 * 发送短信验证码
 * @param $moble 手机号
 * @param $content 验证码
 * @return int  2繁忙 1成功 0失败
 */
function code($moble,$content){

    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL

    $smsConf = array(
        'key' => '4d2a67c6517cdf83e5db72fe542b2dfd', //您申请的APPKEY
        'mobile' => $moble, //接受短信的用户手机号码
        'tpl_id' => '68750', //您申请的短信模板ID，根据实际情况修改
        'tpl_value' => '#code#=' . $content . '&#company#=聚合数据' //您设置的模板变量，根据实际情况修改
        );

    $tmp = cache('code_'.$moble);
    if($tmp && $tmp['time']>time()){
        return 2; //操作太频繁
    }

	$res = mycurl($sendUrl, $smsConf, 1); //请求发送短信

	if ($res) {
        $result = json_decode($res, true);
        $error_code = $result['error_code'];
        if ($error_code != 0) {
			return 0;
        } else {
            $tmp['time'] = time()+120; //频率时间
            $tmp['code'] = $content;  //验证码
            $tmp['num'] = 3;          //验证次数
            $tmp['status'] = 1;       //状态
            cache('code_'.$moble,$tmp,300);
        	return 1;
        }
    }
/*
    $tmp = cache('code_'.$moble);

    if($tmp && $tmp['time']>time()){
        return 2; //操作太频繁
    }

    import('SignatureHelper', EXTEND_PATH);
    $params = array ();

    // *** 需用户填写部分 ***

    //fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = "LTAItcrl48hmFlcR";
    $accessKeySecret = "UUlekDRJYy2yKdxHh9seG93nyCwqHs";

    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = $moble;

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = "郑哥";

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = "SMS_136480088";

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        "code" => $content
    );

    // fixme 可选: 设置发送短信流水号
    // $params['OutId'] = $content;

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    // $params['SmsUpExtendCode'] = "1234567";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    $res = $helper->request(
        $accessKeyId,
        $accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ))
    // fixme 选填: 启用https
    // ,true
    );

    if ($res->Code == 'OK') {
        $tmp['time'] = time()+120; //频率时间
        $tmp['code'] = $content;  //验证码
        $tmp['num'] = 3;          //验证次数
        $tmp['status'] = 1;       //状态
        cache('code_'.$moble,$tmp,300);
        return 1;
    } else {
        return 0;
    }
*/
}
/**
 * 加密方法
 * @param string $str
 * @return string
 */
function encrypt($str,$screct_key){
    //AES, 128 模式加密数据 CBC
    $screct_key = base64_decode($screct_key);
    $str = trim($str);
    $str = addPKCS7Padding($str);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
    $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
    return base64_encode($encrypt_str);
}

/**
 * 解密方法
 * @param string $str
 * @return string
 */
function decrypt($str,$screct_key){
    //AES, 128 模式加密数据 CBC
    $str = base64_decode($str);
    $screct_key = base64_decode($screct_key);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
    $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
    $encrypt_str = trim($encrypt_str);

    $encrypt_str = stripPKSC7Padding($encrypt_str);
    return $encrypt_str;

}

/**
 * 填充算法
 * @param string $source
 * @return string
 */
function addPKCS7Padding($source){
    $source = trim($source);
    $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

    $pad = $block - (strlen($source) % $block);
    if ($pad <= $block) {
        $char = chr($pad);
        $source .= str_repeat($char, $pad);
    }
    return $source;
}
/**
 * 移去填充算法
 * @param string $source
 * @return string
 */
function stripPKSC7Padding($source){
    $source = trim($source);
    $char = substr($source, -1);
    $num = ord($char);
    if($num==62)return $source;
    $source = substr($source,0,-$num);
    return $source;
}
/**
 * 检测手机验证码
 * @param 手机号
 */
function check_code($phone,$code){
    $tmp = cache('code_'.$phone);

    if($tmp['code'] == $code && $tmp['num'] > 0 && $tmp['status'] == 1){
        $tmp['status'] = 0;
        cache('code_'.$phone,$tmp);
        return 1;
    }else{
        if($tmp){
            $tmp['num']--;
            cache('code_'.$phone,$tmp);
        }
        return 0;
    }
}
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function mycurl($url, $params = false, $ispost = 0) {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
	}

/**
 * https请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function https_curl($url,$params=false,$ispost=0){
    $headers = array(
        "Content-type: application/json;charset='utf-8'",
        "Accept: application/json",
    );

    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    $response = curl_exec($ch);
    if ($response === FALSE) {
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
    curl_close($ch);
    return $response;
    }

    /*订单号*/
    function microtime_float()
	{
	   list($usec, $sec) = explode(" ", microtime());
	   $time = microtime_format('YmdHisx',((float)$usec + (float)$sec));
	   return chr(rand(65,90)).$time.mt_rand(1000000,9999999);
	}

	function microtime_format($tag, $time)
	{
	   list($usec, $sec) = explode(".", $time);
	   $date = date($tag,$usec);
	   return str_replace('x', $sec, $date);
	}

	function set_key()
	{
		#data:2016-10-18
		#note:php rsa secret

		//创建公钥和私钥
		$res=openssl_pkey_new(array('private_key_bits' => 2048)); #此处512必须不能包含引号。

		//提取私钥
		openssl_pkey_export($res, $private_key);

		//生成公钥
		$public_key=openssl_pkey_get_details($res);
		

		$public_key=$public_key["key"];
		$a = '/-----BEGIN PUBLIC KEY-----\n([\s\S]*)\n-----END PUBLIC KEY-----/';
		preg_match_all($a, $public_key, $public_keys);
		preg_match_all($a, $public_key, $private_keys);
		$key['public_key'] = $public_keys[1][0]; 
		$key['private_key'] = $private_keys[1][0]; 
		return $key;
		// //要加密的数据
		// $data = "Web site:http://www.04007.cn";
		// echo '加密的数据：'.$data."n";

		// //私钥加密后的数据
		// openssl_private_encrypt($data,$encrypted,$private_key);

		// //加密后的内容通常含有特殊字符，需要base64编码转换下
		// $encrypted = base64_encode($encrypted);
		// echo "私钥加密后的数据:".$encrypted."n";  

		// //公钥解密  
		// openssl_public_decrypt(base64_decode($encrypted), $decrypted, $public_key);
		// echo "公钥解密后的数据:".$decrypted,"n-------------------------------n";  
		  
		// //----相反操作。公钥加密 
		// openssl_public_encrypt($data, $encrypted, $public_key);
		// $encrypted = base64_encode($encrypted);  
		// echo "公钥加密后的数据:".$encrypted."n";
		  
		// openssl_private_decrypt(base64_decode($encrypted), $decrypted, $private_key);//私钥解密  
		// echo "私钥解密后的数据:".$decrypted."n";
	}

/**
 * 加密解密
 * @param  [type]  $string    [description]
 * @param  string  $operation [description] DECODE解密  ENCODE加密
 * @param  string  $key       [description]
 * @param  integer $expiry    [description]encode
 * @return [type]             [description]DECODE
 */
function authcode($string, $operation = 'DECODE', $key = 'wetoken.vip', $expiry = 0) {   
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
    $ckey_length = 4;   
    // 密匙   
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);   
       
    // 密匙a会参与加解密   
    $keya = md5(substr($key, 0, 16));   
    // 密匙b会用来做数据完整性验证   
    $keyb = md5(substr($key, 16, 16));   
    // 密匙c用于变化生成的密文   
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): 
substr(md5(microtime()), -$ckey_length)) : '';   
    // 参与运算的密匙   
    $cryptkey = $keya.md5($keya.$keyc);   
    $key_length = strlen($cryptkey);   
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
//解密时会通过这个密匙验证数据完整性   
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :  
sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
    $string_length = strlen($string);   
    $result = '';   
    $box = range(0, 255);   
    $rndkey = array();   
    // 产生密匙簿   
    for($i = 0; $i <= 255; $i++) {   
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
    }   
    // var_dump($rndkey);exit;
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
    for($j = $i = 0; $i < 256; $i++) {   
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;   
        $tmp = $box[$i];   
        $box[$i] = $box[$j];   
        $box[$j] = $tmp;   
    }   
    // 核心加解密部分   
    for($a = $j = $i = 0; $i < $string_length; $i++) {   
        $a = ($a + 1) % 256;   
        $j = ($j + $box[$a]) % 256;   
        $tmp = $box[$a];   
        $box[$a] = $box[$j];   
        $box[$j] = $tmp;   
        // 从密匙簿得出密匙进行异或，再转成字符   
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
    }   
    if($operation == 'DECODE') {  
        // 验证数据有效性，请看未加密明文的格式   
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
            return substr($result, 26);   
        } else {   
            return '';   
        }   
    } else {   
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
        return $keyc.str_replace('=', '', base64_encode($result));   
    }   
}


/**
 * 获取已过去多久
 * @param int $timestamp 时间戳
 * @return string 显示已过去多久
 */
function getTimePassed($timestamp)
{
    $timestamp = is_int($timestamp) ? $timestamp : strtotime($timestamp);

    $passed = time() - $timestamp;

    if ($passed < 60) {
        return $passed . '秒前';
    } else if ($passed < 3600) {
        return floor($passed / 60) . '分钟前';
    } else if ($passed < 86400) {
        return floor($passed / 3600) . '小时前';
    } else if ($passed < 259200) {
        return floor($passed / 86400) . '天前';
    }else{
    	return date('Y-m-d',$timestamp);
    }
}

/**
 * 生成算式验证码
 * @param  integer $w [description]
 * @param  integer $h [description]
 * @return [type]     [description]
 */
function getValidat($key='',$w=100,$h=30){
    $img = imagecreate($w,$h);
 
    $gray = imagecolorallocate($img,255,255,255);
    $black = imagecolorallocate($img,rand(0,200),rand(0,200),rand(0,200));
    $red = imagecolorallocate($img, 255, 0, 0);
    $white = imagecolorallocate($img, 255, 255, 255);
    $green = imagecolorallocate($img, 0, 255, 0);
    $blue = imagecolorallocate($img, 0, 0, 255);
    imagefilledrectangle($img, 0, 0, 100, 30, $black);
 
 
    for($i = 0;$i < 80;$i++){
        imagesetpixel($img, rand(0,$w), rand(0,$h), $gray);
    }
 

    $num1 = rand(1,99);
    $num2 = rand(1,99);

    $rand = rand(0,1);
    if($rand == 1){
        if($num2>$num1){
            $tmp = $num2;
            $num2 = $num1;
            $num1 = $tmp;
        }
        $sum = $num1-$num2;
        $action = '-';

    }else{
        $action = '+';
        $sum = $num1+$num2;
    }   
    session('validate',$sum);
    cache('ss_'.$key,$sum,300);
    imagestring($img, 5, 5, rand(1,10), $num1, $red);
    imagestring($img,5,30,rand(1,10),$action, $white);
    imagestring($img,5,45,rand(1,10),$num2, $green);
    imagestring($img,5,65,rand(1,10),"=", $blue);
    imagestring($img,5,80,rand(1,10),"?", $red);
     
 
    header("content-type:image/png");
    imagepng($img);
    imagedestroy($img);
}

/**
*算式验证码验证
 */
function check_Validate($num,$key=''){
    $tmp = session('validate');
    session('validate',NULL);
    $tms = cache('ss_'.$key);
    
    cache('ss_'.$key,NULL);
    if($num == ''){
        return false;   
    }
    if($num == $tmp || $num == $tms){
        return true;   
    }
    return false;  
}

//获取用户IP地址
function getIp()
{

    if(!empty($_SERVER["HTTP_CLIENT_IP"]))
    {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else if(!empty($_SERVER["REMOTE_ADDR"]))
    {
        $cip = $_SERVER["REMOTE_ADDR"];
    }
    else
    {
        $cip = '';
    }
    preg_match("/[\d\.]{7,15}/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);

    return $cip;
}

//批量请求
function curls($urls){
    if(!is_array($urls)){
        return;
    }
    $mh = curl_multi_init();
    foreach ($urls as $i => $url) {
        $conn[$i] = curl_init($url);
        curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
        curl_setopt($conn[$i], CURLOPT_HEADER ,0);
        curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT,60);
        curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,true);  // 设置不将爬取代码写到浏览器，而是转化为字符串
        curl_multi_add_handle ($mh,$conn[$i]);
    }

    do {
        curl_multi_exec($mh,$active);
    } while ($active);

 foreach ($urls as $i => $url) {
   $data = curl_multi_getcontent($conn[$i]); // 获得爬取的代码字符串
 } // 获得数据变量，并写入文件

    foreach ($urls as $i => $url) {
        curl_multi_remove_handle($mh,$conn[$i]);
        curl_close($conn[$i]);
    }

    curl_multi_close($mh);
}