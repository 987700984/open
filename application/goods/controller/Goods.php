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
namespace app\goods\controller;

use app\admin\controller\Base;;

use app\goods\model\goodsModel;
use app\shop\model\TpgoodsPrice;
use app\shop\model\TpgoodsType;
use app\shop\model\Tpspec;
use think\Db;

class Goods extends Base
{
    public function index()
    {
        if(request()->isAjax()){
        	
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
            	$where['goodsname'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $goods = new goodsModel();
            $selectResult = $goods->getGoodsByWhere($where, $offset, $limit);
            $goodsstatus=config('goodsstatus');
            $iscommission=config('iscommission');
            if(count($selectResult) > 0){               	
            	foreach($selectResult as $key=>$vo){	
            		$operate = [
            				'编辑' => url('goods/goodsEdit', ['goodsid' => $vo['goodsid']]),
                             '编辑规格' => url('goods/edit_spec', ['goodsid' => $vo['goodsid']]),
            				'删除' => "javascript:goodsDel('".$vo['goodsid']."')"
            		];   
                    $selectResult[$key]['operate'] = showOperate($operate);   
                    $selectResult[$key]['cid'] = $goods->oneCategory($vo['cid']);           
                    $selectResult[$key]['pic'] = '<img src="'.$vo['pic'].'"/ style="width:100px;">';        	
            		$selectResult[$key]['goodsstatus']=$goodsstatus[$vo['goodsstatus']];
                    $selectResult[$key]['iscommission']=$iscommission[$vo['iscommission']];
            	}            	
            	$return['total'] = $goods->getAllGoods($where);
            	$return['rows'] = $selectResult;
            	return json($return);       
            }
        }
        return $this->fetch();
    }

    public function goodsAdd()
    {
        $goods = new goodsModel();

        if(request()->isPost()){

            $file = request()->file('file');
            $param = input('param.');  
            $param = parseParams($param['data']);

            $goodsCon['goodsname']=$param['goodsname'];
            $goodsCon['goodsprice']=$param['goodsprice'];
            $goodsCon['spec_item']=isset($param['spec'])?json_encode($param['spec']):'';
            $goodsCon['pay_type']=isset($param['pay_type'])?json_encode($param['pay_type']):'';
            $goodsCon['goodscreateperson'] = session("id");
            $goodsCon['goodscreatetime'] = date('Y-m-d H:i:s');
            $goodsCon['goodsstatus'] = 0;
            $goodsCon['total'] = $param['total'];
            $goodsCon['old_price'] = $param['old_price'];
            $goodsCon['fjed'] = $param['fjed'];
            $goodsCon['yxzq'] = $param['yxzq'];
            $goodsCon['kjsl'] = $param['kjsl'];
            $goodsCon['is_virtual'] = $param['is_virtual'];
            $goodsCon['goodsstatus'] = $param['goodsstatus'];
            $goodsCon['iscommission'] = $param['iscommission'];
            $goodsCon['content'] = $param['content'];
            $goodsCon['cid'] = $param['cid'];
            $goodsCon['tid'] = $param['tid'];
            $goodsCon['month'] = $param['month'];
            $goodsCon['agent_level'] = $param['agent_level'];
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
                    $pic = $goods->moveOSS($info->getFilename(), $info->getSaveName());
                    $goodsCon['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            // var_dump($goodsCon);exit;
            
            $flag = $goods->insertGoods($goodsCon);
            if($flag['id']){
                $this->log->addLog($this->logData,'进行了商品添加操作');

                if(isset($param['ind'])){
                    foreach ($param['ind'] as $ke=>$va){
                        $dat=[];
                        $dat['key']=$va;
                        foreach ($param['like'] as $k=>$v){
                            foreach ( $v as $key=>$val){
                                if($key==$ke){
                                    $dat[$k]=$val;
                                }
                            }
                        }
                        $dat['goodsid']=$flag['id'];
                        db('tpgoods_price')->insert($dat);
                    }
                }


            }

            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $category = $goods->allCategory();
        $this->assign(['category' => $category]);
        return $this->fetch();
    }

    public function goodsEdit()
    {
    	$goods = new goodsModel();

        if(request()->isPost()){
            $file = request()->file('file');

            $param = input('post.');
            $param = parseParams($param['data']);           
            
            $param['goodsmodperson'] = session("id");
            $param['goodsmodtime'] = date('Y-m-d H:i:s');
            $param['goodsstatus'] = $param['goodsstatus'];
            $param['pay_type']=isset($param['pay_type'])?json_encode($param['pay_type']):'';
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
                    $pic = $goods->moveOSS($info->getFilename(), $info->getSaveName());
                    $param['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                }
            }
            $flag = $goods->editGoods($param);
            $this->log->addLog($this->logData,'进行了商品编辑操作');
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $goodsid = input('param.goodsid'); 
        $onegoods= $goods->getOneGoods($goodsid);
        $rolelist = Db::name('tprole')->select();
        $category = $goods->allCategory();
        $this->assign('goodtype' , db('tpgoods_type')->where('cid',$onegoods['cid'])->select());
        $this->assign(['category' => $category]);
        $this->assign(['goodsid' => $onegoods['goodsid'],'goodsname' => $onegoods['goodsname'],'goodsprice' => $onegoods['goodsprice'],'goodsstatus' => $onegoods['goodsstatus'],'roleid'=>$onegoods['roleid'],'iscommission'=>$onegoods['iscommission'],'total'=>$onegoods['total'],'old_price'=>$onegoods['old_price'],'fjed'=>$onegoods['fjed'],'yxzq'=>$onegoods['yxzq'],'kjsl'=>$onegoods['kjsl'],'pic'=>$onegoods['pic'],'content'=>$onegoods['content'],'cid'=>$onegoods['cid'],'agent_level'=>$onegoods['agent_level'],'month'=>$onegoods['month'],'goods'=>$onegoods]);
        return $this->fetch();
    }

    public function goodsDel()
    {
        $goodsid = input('param.goodsid');

        $role = new goodsModel();
        $flag = $role->delGoods($goodsid);
        $this->log->addLog($this->logData,'进行了商品删除操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    //修改分类规格
    public function edit_spec(){
        $goods = new goodsModel();

        if(request()->isPost()){


            $param = input('post.');

            $param = parseParams($param['data']);

            $data['cid']=$param['cid'];
            $data['goodsid']=$param['goodsid'];
            $data['tid']=$param['tid'];

            if(isset($param['spec'])){
                $data['spec_item']=json_encode($param['spec']);
            }else{
                $data['spec_item']='';
            }

            $data['goodsmodperson'] = session("id");
            $data['goodsmodtime'] = date('Y-m-d H:i:s');


            $flag = $goods->editGoods($data);
            if($flag['code']==0){
                $this->log->addLog($this->logData,'进行了商品分类规格编辑操作');
                $model=new TpgoodsPrice();
                $model->where('goodsid',$param['goodsid'])->delete();

                if(isset($param['like']) && isset($param['ind']) ){
                    foreach ($param['ind'] as $ke=>$va){
                        $dat=[];
                        $dat['key']=$va;
                        foreach ($param['like'] as $k=>$v){
                            foreach ( $v as $key=>$val){
                                if($key==$ke){
                                    $dat[$k]=$val;
                                }
                            }
                        }
                        $dat['goodsid']=$param['goodsid'];
                        db('tpgoods_price')->insert($dat);
                    }
                }

            }

            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $goodsid = input('param.goodsid');
        $onegoods= $goods->getOneGoods($goodsid);
        $lis=$list=[];
        if($onegoods['tid']){
            $model=new Tpspec();
            $keys = Db::name('tpgoodsPrice')->where("goodsid", $goodsid)->value("GROUP_CONCAT(`key` SEPARATOR ',')");
            $spec = array();
            if ($keys) {
                $spec = Db::name('tpspecItem')->alias('a')->join('ims_tpspec b','a.sid=b.sid','LEFT')->where('a.itemid','in',$keys)->group('a.sid')->select();

            }

            if($spec){

                foreach ($spec as $k=>$v){
                    
                    $lis[$k]['spec']=$v;
                    $lis[$k]['item']=$model->spec_item()->where('sid',$v['sid'])->select();
                    

                }
            }
        }
        if($onegoods['spec_item']){
            $goodprice=new TpgoodsPrice();
            $list=$goodprice->where('goodsid',$onegoods['goodsid'])->select();
            foreach ($list as $k=>$v){
                $list[$k]['key_id']=$v['key'];
                $list[$k]['key']=$v->get_key_name(1);
            }
        }

        $this->assign(['lis' => $lis]);
        $this->assign(['list' => $list]);
        $category = $goods->allCategory();
        $this->assign('goodtype' , db('tpgoods_type')->where('cid',$onegoods['cid'])->select());
        $this->assign(['category' => $category]);
        $this->assign(['goods'=>$onegoods]);
        return $this->fetch();
    }


}
