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
namespace app\orders\controller;

use app\admin\controller\Base;

use app\orders\model\ordersModel;
use app\orders\model\goodsModel;
use think\Db;

class Shop extends Base
{
    public function index()
    {
        // $ordertype = Db::name('tporderType')->select();

        if(request()->isAjax()){

            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = 1;
           
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where = 'oid="'.$param['searchText'].'" or phone="'.$param['searchText'].'"';
            }

            $orders = new ordersModel();
            $selectResult = db::name('shop_orderform')->where($where)->order('id desc')->limit($offset,$limit)->select();

            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){	
            		$operate = [
                        // '已发货' =>"javascript:ordersEdit('".$vo['id']."')",
            			// '编辑' => url('orders/ordersEdit', ['id' => $vo['id']]),
            			'删除' => "javascript:Del('".$vo['id']."')"
            		];           	
                    $selectResult[$key]['operate'] = showOperate($operate);
            		$selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);
                    // $selectResult[$key]['goods'] = '<a href="javascript:goods('.$vo['id'].')" >详情</a>';

                    

            	}  

            	$return['total'] = db::name('shop_orderform')->where($where)->count();
            	$return['rows'] = $selectResult;
            	return json($return);
            }
        }
        // $this->assign('type',$ordertype);
        return $this->fetch();
    }


    public function Del()
    {
        $id = input('param.id');

        // $role = new ordersModel();
        $res = db::name('shop_orderform')->where('id', $id)->delete();
        // $flag = ->delorders($ordersid);
        if ($res) {
            $flag = ['code' => 0, 'data' => '', 'msg' => '删除成功'];
        }else{
            $flag = ['code' => 1, 'data' => '', 'msg' => '删除失败'];
        }
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

}
