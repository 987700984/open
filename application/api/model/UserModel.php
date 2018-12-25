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
namespace app\api\model;

use think\Model;

class UserModel extends Model
{
    protected $table = 'ims_tpuser';

    /*****
     * 充值金额
     */

    public function  charge($param,$agent,$uid){

        if(intval($param['num'])<=0){
            return false;
        }
//        $this->money+=$param['num'];
//        $this->save();
//        $this->write_log('购买矿机',$param['num'],$uid,0,1);
        $this->commission($agent,$param,$uid);
    }

    /******
     * 佣金返还
     */
    public function commission($agent,$param,$uuid){
        /*** 下级好友代理分拥  ******/
        $model=new UserAgent();
        $is_agent=1;
        if($agent){
            if($agent->leve->level>=2 && $agent['agentid']>0 ){

                $level=3;
                if($level){
                    $this->digui_agent($model,$agent,$param['num'],$level,$agent['uid']);
                    $is_agent=2;
                }
            }

        }

        /*** 一级好友代理分拥  ******/
        $user=$this->where(['id'=>$uuid])->find();

        if($user->rtid!=0){
            $rtid=$user->rtid;
            $us=UserModel::where(['phone'=>$rtid])->find();
            $uid=$us['id'];
            $agentid=$model->agent_add($uid,$param['sid']);
            $agent=$model->get($agentid);

//            $info=$this->where(['id'=>$agent['uid']])->find();
            $commission=$param['num']*$agent->leve->commission1/100;
            /*** 修改金额  ******/
            if($commission>0){
//                $info->money+=$commission;
//                $info->save();
                /*** 写入流水  ******/
                $this->write_log('一级好友分拥',$commission,$agent['uid'],$uuid,0,$param['sid'],0,1);
                $agent->money+=$commission;
                $agent->commission1+=$commission;
                $agent->save();
            }
            if($agent['agentid']>0 && $is_agent==1){

                if($agent->leve->level>=2 && $agent['agentid']>0){
                    $level=3;
                    if($level){
                        $this->digui_agent($model,$agent,$param['num'],$level,$agent['uid']);
                    }
                }
                $is_agent=2;
            }
        }

        /*** 二级好友代理分拥  ******/
        if($user->rtid2!=0){
            $model1=new UserAgent();
            $rtid=$user->rtid2;
            $us1=UserModel::where(['phone'=>$rtid])->find();
            $uid=$us1['id'];
            $agentid=$model1->agent_add($uid,$param['sid']);
            $agent1=$model1->get($agentid);
//            $info1=$this->where(['id'=>$agent1['uid']])->find();
            $commission=$param['num']*$agent1->leve->commission2/100;
            /*** 修改金额  ******/
            if($commission>0){
//                $info1->money+=$commission;
//                $info1->save();
                /*** 写入流水  ******/
                $this->write_log('二级好友分拥',$commission,$agent1['uid'],$uuid,0,$param['sid'],1,1);
                $agent1->money+=$commission;
                $agent1->commission1+=$commission;
                $agent1->save();
            }
            if($agent1['agentid']>0 && $is_agent==1){
                if($agent1->leve->level>=2 && $agent1['agentid']>0){
                    $level=3;
                    if($level){
                        $this->digui_agent($model,$agent1,$param['num'],$level,$agent['uid']);
                    }
                }
            }
        }

    }

    private function digui_agent($model,$agent,$num,$level,$uuid){
        if($level){
            $fat_agent =$model->where(['id'=>$agent['agentid'],'sid'=>$agent['sid']])->find();
            if($fat_agent){
//                $info=$this->where(['id'=>$fat_agent['uid']])->find();
                $com='commission'.($level+2);
                $commission=$num*$fat_agent->leve->$com/100;
                /*** 修改金额  ******/
                if($commission>0){

                    $agent->commission2+=$commission;
                    $agent->save();
                    /*** 写入流水  ******/

                    $msg='代理分拥';
                    $this->write_log($msg,$commission,$fat_agent['uid'],$uuid,0,$fat_agent['sid'],2);
                    $fat_agent->commission+=$commission;
                    $fat_agent->money+=$commission;
                    $fat_agent->save();
                    $level--;
                    $this->digui_agent($model,$fat_agent,$num,$level,$fat_agent['uid']);
                }
            }


        }

    }

    /***
     * 写入流水
     ******/
    public function write_log($msg,$commission,$uid,$uuid=0,$type=0,$sid=7,$type3=0,$type2=0){
        $log=new MoneyLog();
        $log->uid=$uid;
        $log->agentid=$uuid;
        $log->addtime=time();
        $log->content=$msg;
        $log->type=$type;
        $log->sid=$sid;
        $log->type3=$type3;
        $log->is_friend=$type2;
        $log->agent_price=$commission;
        $log->save();
    }

}