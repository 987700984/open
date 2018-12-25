<?php
namespace app\api\controller;
use think\Cache;
use think\Db;

class Dbug extends Base
{
    public function index(){
        dump(5>=2);
        switch (5>=2){
            case 2:
                echo 2;
                break;
            case 3:
                echo 3;
                break;
            case 4:
                echo 4;
                break;
        }
        echo "前方高能，非战斗人员赶紧撤离！！！";
    }

    //调试短信验证码
    public function code(){
        $phone = get_input_data('phone');
        $data = Cache::get('code_'.$phone);
        return json($data);
    }

    public function test(){
//        $image = \think\Image::open('./image.png');
//        // 给原图左上角添加水印并保存water_image.png
//        $image->water('./logo.png',1)->water('./image.png')->save('water_image.png');

        for($i=0;$i<10;$i++){
            dump($i);
        }
    }

    public function dd($oid=''){
        if(empty($oid)){
            $oid = get_input_data('oid');
        }
        dump($oid);
    }


}