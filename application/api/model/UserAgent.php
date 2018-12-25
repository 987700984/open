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

use app\admin\controller\Level;
use think\Model;

class UserAgent extends Model
{


    /**
     * 与user表关联
     * @param $param
     */
    public function user()
    {
        return $this->hasone('UserModel','id','uid');

    }


    /**
     * 与agent_level表关联
     * @param $param
     */
    public function leve()
    {
        return $this->belongsTo('AgentLevel','level','id');
    }


    /**
     * 获取代理数量
     * @param $param
     */
    public  function get_children_count(){
            $id=$this->id;
            return $this->where(['agentid'=>$id])->count();
    }

    /**
     * 获取邀请好友数量
     * @param $param
     */
    public  function get_friend_count(){
        $phone=$this->user->phone;
         $use=$this->user();
        $count=$use->where(['rtid'=>$phone])->count();
        return $count;
    }

    /**
     * 获取总好友数量
     * @param $param
     */
    public  function get_allfriend_count(){
        $id=$this->id;
        $use=$this->user();
        $phone=$this->user->phone;
        return $use->where(['rtid2'=>$phone])->count();
    }

    /*****
     * 递归获取好友数量
     */
    private function digui($id,$count=0){
            $coun=$this->where(['agentid'=>$id])->select();
            $count+=count($coun);
            foreach ($coun as $k=>$v){
                $count=$this->digui($v['id'],$count);
            }
            return $count;
    }

    /*****
     * 升降级
     */
    public function handle_level($agentinfo_id,$l,$levelinfo){

        if($l<0){
            $notice['title']='代理等级降级通知';
        }else{
            $notice['title']='代理等级升级通知';
        }
        $notice['is_personal']=1;
        $notice['addtime']=time();
        $notice['sid']=$levelinfo;
       $this->digui_handle($agentinfo_id,$l,$levelinfo,$notice);

    }

    /*****
     * 检测是否是代理并自动创建
     */
    public function agent_add($uid,$sid=7){
        if( $agent=$this->where(['uid'=>$uid,'sid'=>$sid])->find()){
            return $agent->id;
        }else{
            $levelinfo=$this->leve()->where(['level'=>1,'sid'=>$sid])->find();
            $data=[
                'add_time'=>time(),
                'uid'=>$uid,
                'sid'=>$sid,
                'level'=>$levelinfo['id'],
                'agentid'=>0
            ];
            $this->save($data);
 
            return $this->id;
        }





    }

    /*****
     * 购买代理
     */
    public function buy_agent($data){
        $agent=$this->where(['uid'=>$data['uid'],'sid'=>$data['sid']])->find();
        if($agent){
            if($agent['agentid']>0){
                return ['status'=>0,'msg'=>'请先解除代理身份'];
            }
            $level=$agent->leve->level;
            if($level>=$data['level']){
                return ['status'=>0,'msg'=>'当前等级大于购买等级'];
            }
            $agentid=$agent['id'];
            $l=$data['level']-$level;
            $this->digui_handle($agentid,$l,$data['sid']);
        }else{
            $levelinfo=$this->leve()->where(['level'=>$data['level'],'sid'=>$data['sid']])->find();
            $data=[
                'add_time'=>time(),
                'uid'=>$data['uid'],
                'level'=>$levelinfo['id'],
                'agentid'=>0
            ];
            $this->save($data);
        }



    }



    /*****
     * 递归升降级
     */

    private  function  digui_handle($id,$level,$sid,$notice=[]){
        $agent=$this->where(['id'=>$id,'sid'=>$sid])->find();
         $leve=$agent->leve->level+$level;
         if($leve<=1){

             $agent->agentid=0;
             $leve=1;
         }
         $levelmodel=new AgentLevel();
         $levelinfo=$levelmodel->where(['level'=>$leve,'sid'=>$sid])->find();
         if(!$levelinfo){
             return false;
         }
         if($level>0){
             $notice['content']='您的代理等级已经升为'.$levelinfo['name'];
         }else{
             $notice['content']='您的代理等级已经降为'.$levelinfo['name'];
         }
        $notice['uid']=$agent['uid'];
        $agent->level=$levelinfo['id'];
        $status= $agent->save();
        if($status){
            $model=new PerNew();
            $model->save($notice);
        }
        $child=$this->where(['agentid'=>$agent['id'],'sid'=>$sid])->select();

        if($child){
            foreach ($child as $k =>$v){

                   $this->digui_handle($v['id'],$level,$sid,$notice);

            }
        }
    }







}