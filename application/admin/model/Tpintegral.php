<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;
class Tpintegral extends Model
{




    /**
     * 与user表关联
     * @param $param
     */
    public function user()
    {
        return $this->belongsTo('UserAgent','uid','id');
    }

    /*****
     * 充值积分
     */

    public function  charge($param,$uid,$conid=0,$msg='后台发币即时到账',$type=0,$type2=7){
        $info=$this->where(['uid'=>$uid,'sid'=>$param['sid']])->find();
        if($info){
            $info->integral+=$param['amount'];
            $status=$info->save();
        }else{
            $model=new Tpintegral();
            $model->uid=$uid;
            $model->sid=$param['sid'];
            $model->integral=$param['amount'];
            $model->addtime=time();
            $status=$model->save();
        }
        if($status){
            $status=$this->write_log($msg,$param['sid'],$param['amount'],$uid,$conid,0,$type,$type2);
            if($status){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }





    /***
     * 写入流水
     ******/
    private function write_log($msg,$sid,$commission,$uid,$coin_id=0,$uuid=0,$type=0,$type2=6){
        $log=new Tpbill();
        if($type==1){
            $commission=0-$commission;
        }else{
        }
        $data=[
            'sid'=>$sid,
            'uid'=>$uid,
            'agentid'=>$uuid,
            'addtime'=>time(),
            'content'=>$msg,
            'type2'=>$type2,
            'type'=>$type,
            'coin_id'=>$coin_id,
            'price'=>$commission,
        ];
        $st=$log->save($data);
        if($st){
            return true;
        }else{
            return false;
        }
    }







}