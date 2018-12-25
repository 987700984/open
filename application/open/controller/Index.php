<?php
namespace app\open\controller;

use app\open\model\apiModel;
use think\Request;
use \think\Image;
use \think\Captcha;
use \think\Loader;
use think\Db;



class Index extends Common
{

	public function index(){
        $data['param'] = Request::instance()->param();
        $data['header'] = Request::instance()->header();
        dump($data);

	}
	

}

