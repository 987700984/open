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
namespace app\coinlist\controller;

use app\admin\controller\Base;;
use think\Db;
use app\coinlist\model\coinlistModel;

class Coinlist extends Base
{
    //文章列表
    public function index()
    {
        if(request()->isAjax()){
        	
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];

            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }


            // var_dump($where);
            $coinlist = new coinlistModel();
            $selectResult = $coinlist->getCoinlistByWhere($where, $offset, $limit);
            $arr = array('禁用', '启用');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){
            		$operate = [
            				'编辑' => url('coinlist/coinlistEdit', ['id' => $vo['id']]),
            				'删除' => "javascript:coinlistDel('".$vo['id']."')"
            		];           	
                    $selectResult[$key]['operate'] = showOperate($operate);             
                    $selectResult[$key]['status'] = $arr[$vo['status']];            
            		$selectResult[$key]['pic'] = '<img style="width:60px;" src="'.$vo['pic'].'">';          	
            	}            	
                $count = $coinlist->getCoinlistAll($where);
            	$return['total'] = count($count);  //总数据
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    //添加文章
    public function coinlistAdd()
    {   
        $coinlist = new coinlistModel();

        if(request()->isPost()){
            $file = request()->file('file');
           

            $param = input('param.');
            $param = parseParams($param['data']);
            $param['address'] = strtolower($param['address']);
            $param['symbol'] = strtoupper($param['symbol']);

            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    // echo $info->getExtension();
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getFilename(); 
                    $pic = $coinlist->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $url = 'https://geth.168erp.cn/wallet/coin';
            $post_data = '{"c_contract_addr": "'.$param['address'].'","c_decimals": '.$param['decimals'].',"c_name": "'.$param['name'].'","c_order": '.$param['c_order'].',"c_symbol": "'.$param['symbol'].'","id": 0,"is_default_show": '.$param['is_default_show'].',"keyWords": "'.$param['keywords'].'"}';
            $res = https_curl($url,$post_data,1);
            $res=json_decode($res,true);
            if ($res['status'] == -1) {
                $flag = '错误的合约地址!';
                return json(['code' => 0, 'data' => '', 'msg' => $flag]);

            }
            $flag = $coinlist->insertCoinlist($param);
            $this->log->addLog($this->logData,'进行了代币添加操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $where1 = [];
        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        $this->assign('soretype',$tpsoretype);

        return $this->fetch();
    }

    //编辑文章
    public function coinlistEdit()
    {
    	$coinlist = new coinlistModel();


        if(request()->isPost()){
            $file = request()->file('file');

            $param = input('post.');
            $param = parseParams($param['data']);
            $param['address'] = strtolower($param['address']);
            $param['symbol'] = strtoupper($param['symbol']);
            
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    // echo $info->getExtension();
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    // echo $info->getFilename(); 
                    $pic = $coinlist->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $url = 'https://geth.168erp.cn/wallet/coin';
            $post_data = '{"c_contract_addr": "'.$param['address'].'","c_decimals": '.$param['decimals'].',"c_name": "'.$param['name'].'","c_order": '.$param['c_order'].',"c_symbol": "'.$param['symbol'].'","id": 0,"is_default_show": '.$param['is_default_show'].',"keyWords": "'.$param['keywords'].'"}';
            $res = https_curl($url,$post_data,1);
            $res=json_decode($res,true);
            if ($res['status'] == -1) {
                $flag = '错误的合约地址!';
                return json(['code' => 0, 'data' => '', 'msg' => $flag]);

            }


            $flag = $coinlist->editCoinlist($param);
            $this->log->addLog($this->logData,'进行了代币编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id'); 
        $onecoinlist= $coinlist->getOneCoinlist($id);      
     
        $this->assign(['id' => $onecoinlist['id'],'name' => $onecoinlist['name'],'address' => $onecoinlist['address'],'pic' => $onecoinlist['pic'],'c_order' => $onecoinlist['c_order'],'is_default_show' => $onecoinlist['is_default_show'],'status' => $onecoinlist['status'], 'decimals'=>$onecoinlist['decimals'], 'symbol'=>$onecoinlist['symbol'], 'keywords'=>$onecoinlist['symbol'],'sid'=>$onecoinlist['sid']]);

        $where1 = [];
        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();
        $this->assign('soretype',$tpsoretype);

        return $this->fetch();
    }

    //删除文章
    public function coinlistDel()
    {
        $id = input('param.id');

        $role = new coinlistModel();
        $flag = $role->delCoinlist($id);
        $this->log->addLog($this->logData,'进行了代币删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
