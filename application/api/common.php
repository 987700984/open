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

// 应用公共文件

function  postCurl($postdata,$url){

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response=curl_exec($ch);
    return  $response;
    
}

//生成钱包地址
function get_wetoken_url($uid) {
    $sha=sha1('wetoken'.$uid);
    if ($uid%2==0){
       $res=substr($sha,0,30);
    }else{
        $res=substr($sha,-30);
    }

    return '0x'.$res;
}

//接收方式
    function get_input_data($key='',$default=null){
        if(file_get_contents("php://input")){
            $str = file_get_contents("php://input");
            $post = json_decode($str,true);
            if($post){
                if($key){
                    if(isset($post[$key])){
                        return htmlspecialchars($post[$key]);
                    }
                    return input($key,$default);
                }
                foreach ($post as $k=>$v){
                    $post[$k]=htmlspecialchars($v);

                }
                return $post;
            }else{
                return input($key,$default);
            }
            return input($key,$default);

        }

        if($key){
            return   input($key,$default);
        }
        $post=input('param.',$default);
        return $post;
    }



