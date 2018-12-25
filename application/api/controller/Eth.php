<?php
namespace app\api\controller;
use think\Request;
class Eth extends Common
{
    private $url = 'https://geth.168erp.cn/';

    public function index(){
        $url = get_input_data('url');
        $data = htmlspecialchars_decode(input('data'));
        if($data){
            return https_curl($this->url.$url,$data,1);
        }else{
            return https_curl($this->url.$url);
        }
//        echo(https_curl($this->url.$url,$data,1));
//        return https_curl($this->url.$url,$data,0);
    }

    //创建钱包
    public function createWallet(){
        $url = 'f0e5cb30a497b76fb50f12f343bba9e214e44/wallet/create';
        $pram =  file_get_contents("php://input");
        if(!$pram){
            $data = get_input_data();
        }else{
            $data = json_decode($pram,true);
        }

        if(empty($data['m_name'])){
            $this->data['msg'] = 'm_name为空';            
            return json($this->data);
        }

        if(empty($data['pwd'])){
            $this->data['msg'] = 'pwd为空';           
            return json($this->data);
        }
        
        $time = time();
        $data['timestamp'] = $time;
        $data['sign'] = md5($data['m_name'].'WeToken'.$data['pwd'].'Wallet'.$time);

        return https_curl($this->url.$url,json_encode($data),1);
    }




}