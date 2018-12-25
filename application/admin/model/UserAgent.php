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

class UserAgent extends Model
{

    /**
     * 插入代理信息
     * @param $param
     */
    public function insertRole($param)
    {
        try{

            $result =  $this->validate('RoleValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加代理成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
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
     * 编辑代理信息
     * @param $param
     */
    public function editRole($param)
    {
        try{

            $result =  $this->validate('RoleValidate')->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑代理成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /*****
     * 升降级
     */
    public function handle_level($level,$sid,$id){



        $this->digui_handle($id,$level,$sid);

    }

    /*****
     * 递归升级
     */

    private  function  digui_handle($id,$level,$sid){
        $agent=$this->where(['id'=>$id])->find();

        $leve=$agent->leve->level+$level;

        $evel=$this->leve()->where(['sid'=>$sid,'level'=>$leve])->find();

        if(!$evel){
            return false;
        }
        $agent->level=$evel['id'];
        if($leve<=1 || $leve>=5 ){
            $evel=$this->leve()->where(['sid'=>$sid,'level'=>$leve])->find();
            $agent->agentid=0;
        }
        $agent->save();
        $child=$this->where(['agentid'=>$agent['id']])->select();
        if($child){
            foreach ($child as $k =>$v){

                $this->digui_handle($v['id'],$level,$sid);

            }
        }
    }





}