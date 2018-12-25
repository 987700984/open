<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 18:01
 */
namespace app\admin\controller;
use app\admin\model\CoinUserAdmin;
use app\admin\model\Tpintegral;
use app\admin\model\Tpsoretype;
use think\Db;

class Coin extends Base
{

    /*发币记录列表*/
    public function index(){

            if(request()->isAjax()) {

                $param = input('param.');

                $limit = $param['pageSize'];
                $offset = ($param['pageNumber'] - 1) * $limit;
                $where1=[];
                $where = [];
                if (isset($param['searchText']) && !empty($param['searchText'])) {
                    $where1['w.username'] = ['like', '%' . $param['searchText'] . '%'];
                    $where1['w.phone'] = ['like', '%' . $param['searchText'] . '%'];
                }
                if (!empty($param['status']) || $param['status']=='0') {
                    $where['a.status'] = ['eq', $param['status']];
                }

                if(session('soretype')){
                    $str = '';
                    $soretypes = session('soretype');
                    foreach ($soretypes as $value){
                        $str .= $value.',';
                    }
                    $str = rtrim($str, ',');
                    $where['a.sid']= ['eq',$str];
                }

                if (isset($param['sid']) && !empty($param['sid'])) {
                    $where['a.sid'] = ['eq', $param['sid']];
                }
                $where['is_delete']=0;
                $user = new CoinUserAdmin();

                if($where1){
                    $selectResult = $user->alias('a')->field('a.*,w.username,w.phone')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->where(function ($q)use($where1){
                        $q->whereOr($where1);
                    })->limit($offset, $limit)->order('a.id desc')->select();
                }else{
                    $selectResult = $user->alias('a')->field('a.*,w.username,w.phone')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->limit($offset, $limit)->order('a.id desc')->select();
                }
                foreach ($selectResult as $key => $vo) {
                    $selectResult[$key]['typename'] = $vo->typ->name;
                    $selectResult[$key]['levelname'] = $vo->tpi->name;
                    $selectResult[$key]['addtime'] = date('Y-m-d H:i:s', $vo['addtime']);
                    if($vo['is_instant']=='即时发放'){
                        $operate = [
                            '删除'=>"javascript:coinDel('".$vo['id']."')"
                        ];
                        $selectResult[$key]['operate'] =showOperate($operate);

                    }else{
                        if($vo['no_amount']!=0){
                            switch( $vo['status']){
                                case '暂未开始':
                                    $operate=['开始发放'=>"javascript:up_status('".$vo['id']."','2')"];
                                    break;
                                case '发放中':

                                    $operate=['停止发放'=>"javascript:up_status('".$vo['id']."','3')"];
                                    break;
                                case '停止发放':

                                    $operate=['恢复发放'=>"javascript:up_status('".$vo['id']."','2')"];
                                    break;
                                case '发放完毕':
                                    break;
                            }

                        }
                        $operate['删除']="javascript:coinDel('".$vo['id']."')";
                        $selectResult[$key]['operate'] =showOperate($operate);

                    }


//
                }
                $return['total'] = $user->alias('a')->join('__TPUSER__ w', 'a.uid = w.id')->where($where)->count();  //总数据
                $return['rows'] = $selectResult;

                return json($return);
            }

            $where1 = [];
            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');
                foreach ($soretypes as $value){
                    $str .= $value.',';
                }
                $str = rtrim($str, ',');
                $where1['id'] = array('in',$str);
            }

                $user = new Tpsoretype();
                $this->assign('level', $user->where($where1)->where(1)->select());
               return $this->fetch();

    }

    /*后台发币*/
    public function  add_coin(){
        if(request()->isPost()){

            $param = input('param.');
            $param = parseParams($param['data']);
            $param['addtime'] =time();
            $model=new CoinUserAdmin();
            if($param['is_employees']==1){
                /*内部员工*/
                if($param['is_instant']==1){
                    $tpi=new Tpintegral();
                    $tpi->charge($param,$param['uid']);
                    unset($param['timing']);
                    $param['cash_amount']=$param['amount'];
                    $param['status']=1;
                    $param['buytime']=time();
                    $status=$model->save($param);
                    if($status){
                       $this->log->addLog($this->logData,'进行了用户后台发币操作');
                        return json(['code' =>1, 'data' => '', 'msg' => '添加成功']);
                    }else{
                        return json(['code' =>-1, 'data' => '', 'msg' => '添加失败']);
                    }
                }else{
                    if($param['timing']<1){
                        return json(['code' =>-1, 'data' => '', 'msg' => '发放天数必须大于0']);
                    }
                    $coin=$model->where(['uid'=>$param['uid'],'sid'=>$param['sid'],'is_instant'=>2,'status'=>0])->find();
                    if($coin){
                       $coin->no_amount+=$param['amount'];
                        $coin->amount+=$param['amount'];
                        $coin->save();
                        return json(['code' =>1, 'data' => '', 'msg' => '添加成功']);
                    }else{
                        $param['no_amount']=$param['amount'];
                        $param['buytime']=time();
                        $flag =$model->insertCoin($param);
                       $this->log->addLog($this->logData,'进行了用户后台发币操作');
                        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                    }

                }
            }else{
                /*普通客户*/
                if($param['is_instant']==1){
                    $tpi=new Tpintegral();
                    $tpi->charge($param,$param['uid']);
                    unset($param['timing']);
                    $param['cash_amount']=$param['amount'];
                    $param['status']=1;
                    $param['buytime']=time();
                    $status=$model->save($param);
                    if($status){
                       $this->log->addLog($this->logData,'进行了用户后台发币操作');
                        return json(['code' =>1, 'data' => '', 'msg' => '添加成功']);
                    }else{
                        return json(['code' =>-1, 'data' => '', 'msg' => '添加失败']);
                    }
                }else{
                    if($param['timing']<1){
                        return json(['code' =>-1, 'data' => '', 'msg' => '发放天数必须大于0']);
                    }
                    $param['no_amount']=$param['amount'];
                    $param['buytime']=time();
                    $flag =$model->insertCoin($param);
                   $this->log->addLog($this->logData,'进行了用户后台发币操作');
                    return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                }
            }



        }

        $user = new Tpsoretype();
        $typ=new \app\admin\model\CoinType();
        $this->assign('uid',input('param.uid'));
        $this->assign('level', $user->where(1)->select());
        $this->assign('type', $typ->where(1)->select());
        return $this->fetch();
    }

    //代理商发币
    public function  add(){
        if(request()->isAjax()){
            $param = input('param.');
            $param = parseParams($param['data']);

            //判断是否为手机号
            if(preg_match("/^1\d{10}$/",$param['uid'])){
                $param['uid'] = Db::name('tpuser')->where(['phone'=>$param['uid']])->value('id');
            }

            $param['typeid'] = 1;
            $param['is_employees'] = 0;
//            $param['is_instant'] = 2;

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($param['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $param['addtime'] =time();
            $model=new CoinUserAdmin();

                /*普通客户*/
                if($param['is_instant']==1){
                    $tpi=new Tpintegral();
                    $tpi->charge($param,$param['uid']);
                    unset($param['timing']);
                    $param['cash_amount']=$param['amount'];
                    $param['status']=1;
                    $param['buytime']=time();
                    $status=$model->save($param);
                    if($status){
                        $this->log->addLog($this->logData,'进行了用户后台发币操作');
                        return json(['code' =>1, 'data' => '', 'msg' => '添加成功']);
                    }else{
                        return json(['code' =>-1, 'data' => '', 'msg' => '添加失败']);
                    }
                }else{

                    if($param['timing']<1){
                        return json(['code' =>-1, 'data' => '', 'msg' => '发放天数必须大于0']);
                    }
                    $param['no_amount']=$param['amount'];
                    $param['buytime']=time();
                    $flag =$model->insertCoin($param);
                    $this->log->addLog($this->logData,'进行了用户后台发币操作');
                    return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                }


        }
        $where1 = [];
        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = array('in',$str);
        }
        $user = new Tpsoretype();
        //$typ=new \app\admin\model\CoinType();
        //$this->assign('uid',input('param.uid'));
        $this->assign('level', $user->where($where1)->where(1)->select());
        //$this->assign('type', $typ->where(1)->select());
        return $this->fetch();
    }


    /*修改发币状态*/

    public function up_status(){
        if(request()->isPost()){
            $param = input('param.');
            $model=CoinUserAdmin::get($param['id']);

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($model['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            $model->status=$param['status'];
            $status=$model->save();
            if($status){
                $content=$param['status']==3?'进行了会员发糖果停止发放操作':'进行了会员发糖果恢复发放操作';
               $this->log->addLog($this->logData,$content);
                return json(['code' =>1, 'data' => '', 'msg' => '修改成功']);
            }else{
                return json(['code' =>-1, 'data' => '', 'msg' => '修改失败']);
            }
        }
    }

    /*软删除*/

    public function delete(){
        if(request()->isPost()){
            $param = input('param.');
            $model=CoinUserAdmin::get($param['id']);
            if($model->getData('status')==2){
                return json(['code' =>-1, 'data' => '', 'msg' => '请先停止发放再删除']);
            }
            $model->is_delete=1;
            $status=$model->save();
            if($status){
               $this->log->addLog($this->logData,'进行了会员发糖果删除操作');
                return json(['code' =>1, 'data' => '', 'msg' => '删除成功']);
            }else{
                return json(['code' =>-1, 'data' => '', 'msg' => '删除失败']);
            }
        }
    }


}