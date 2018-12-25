<?php
namespace  app\api\controller;


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 14:20
 */
class Orders extends Common {

    public function _initialize(){
        parent::_initialize();
        $this->model=new \app\shop\model\Order();
    }

    //订单首页接口
    public function index(){
        $data=get_input_data();
        $uid=session('user.id');
        $row = get_input_data('row') ? get_input_data('row') : 20;
        $p = get_input_data('p') ? get_input_data('p') : 1;
        $where=['uid'=>$uid,'is_delete'=>0];
        if( isset($data['type']) && $data['type']!='-2'){
            $where['type']=$data['type'];
        }
        if(isset($data['state']) && $data['state']!='-2'){

            $where['state']=$data['state'];
        }
        $list=$this->model->field('id,type,state,total_money,phone')->where($where)->limit($row)->page($p)->order('id desc')->select();
        $count=$this->model->where($where)->count();

        foreach ($list as $k=>$v){
            $list[$k]=$v->toArray();
            $list[$k]['state']=$v->getData('state');
            $list[$k]['type']=$v->cate->title;
            unset( $list[ $k]['cate']);
            $lis=$v->orderinfo()->field('name,money,num,key,goodsid')->where('oid',$v['id'])->select();
            foreach ($lis as $ke=>$va){
                $lis[ $ke]['image']=$va->goods->pic;
                unset( $lis[ $ke]['goods']);
            }
            $list[$k]['goods']=$lis;
        }
        return json(['status' => 1, 'msg' => '获取数据成功','data'=>['list'=>$list,'total'=>$count]]);
    }

    //订单详情
    public function detail(){
        $id=get_input_data('id');
        if(!$id){
            return json(['status' => 0, 'msg' => 'id参数错误']);
        }
        $uid=session('user.id');
        $info=$this->model->field('id,type,state,total_money,address,phone,courier,name,company,uptime,oid,msg')->where(['id'=>$id,'uid'=>$uid])->find();
        if(!$info){
            return json(['status' => 0, 'msg' => '订单不存在']);
        }
        $info['kuaidi']=$info['kuaidi'];

        $lis=$info->orderinfo()->where(['oid'=>$id])->select();
        foreach ($lis as $ke=>$va){
            $lis[ $ke]['image']=$va->goods->pic;
            unset( $lis[ $ke]['goods']);
        }
        $info['goods']=$lis;
        $state=$info->getData('state');
        $time=$info->getData('uptime');
        $info=$info->toArray();
        $info['uptime']=$time;
        $info['state']=$state;
        return json(['status' => 1, 'msg' => '获取数据成功','data'=>$info]);
    }

    //订单删除
    public function delete(){
        $id=get_input_data('id');
        if(!$id){
            return json(['status' => 0, 'msg' => 'id参数错误']);
        }
        $uid=session('user.id');
        $info=$this->model->where(['id'=>$id,'uid'=>$uid])->find();
        if(!$info){
            return json(['status' => 0, 'msg' => '订单不存在']);
        }
        $info=$info->save(['is_delete'=>1]);
        if($info){
            return json(['status' => 1, 'msg' => '删除成功']);
        }else{
            return json(['status' => 0, 'msg' => '删除失败']);

        }
    }

    //确认收货
    public function shouhuo(){
        $id=get_input_data('id');
        if(!$id){
            return json(['status' => 0, 'msg' => 'id参数错误']);
        }
        $uid=session('user.id');
        $info=$this->model->where(['id'=>$id,'uid'=>$uid])->find();
        if(!$info){
            return json(['status' => 0, 'msg' => '订单不存在']);
        }
        if($info->getData('state')!=3){
            return json(['status' => 0, 'msg' => '非法操作']);
        }
        $info=$info->save(['state'=>4]);
        if($info){
            return json(['status' => 1, 'msg' => '收货成功']);
        }else{
            return json(['status' => 0, 'msg' => '收货失败']);

        }
    }


}