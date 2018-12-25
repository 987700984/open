<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2099 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\miner\controller;

use app\admin\controller\Base;;

use think\Db;

class Miner extends Base
{
    public function index()
    {
        if(request()->isPost()){
            $param = input('param.');
            $param = parseParams($param['data']);
            db::name('miner')->where('id=1')->update($param);
            $this->log->addLog($this->logData,'进行了矿机配置更新操作');
            return json(['code' => 1, 'data' => '', 'msg' => '更新成功']);

        }
        $miner = db::name('miner')->find();
        $this->assign(['initial' => $miner['initial'], 'price' => $miner['price'], 'force' => $miner['force'], 'degree' => $miner['degree'], 'data' => $miner['data']]);
        return $this->fetch();
    }

   

}
