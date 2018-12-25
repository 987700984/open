<?php
namespace app\open\controller;


class Common extends Base
{
	protected function _initialize(){

		parent::_initialize();
		$user = session('customer');
		if(!$user){
            $this->success('请先登录', 'Login/index');
		}		

	}
}