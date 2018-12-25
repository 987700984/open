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

class AgentLevel extends Model
{

    /**
     * 插入角色信息
     * @param $param
     */
    public function insertRole($param)
    {
        try{

            $result =  $this->validate( [
                'name'  => 'require|max:50',
                'level'   => 'in:1,2,3,4,5',
                'commission1'=>'>=:0|number',
                'commission2'=>'>=:0|number',
                'commission3'=>'>=:0|number'

            ],
                [
                    'name.require' => '名称必须',
                    'name.max'     => '名称最多不能超过25个字符',
                    'level'        => '等级必须在1到5之内',
                    'commission1'=>'推荐一级分拥不能为负数',
                    'commission2'=>'推荐二级分拥不能为负数',
                    'commission3'=>'下级代理分拥不能为负数',
                    'commission1.number'=>'推荐一级分拥格式不对',
                    'commission2.number'=>'推荐二级分拥格式不对',
                    'commission3.number'=>'下级代理分拥格式不对',
                ])->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加角色成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 与agent表关联
     * @param $param
     */
    public function agent()
    {
        return $this->hasMany('UserAgent','level','level');
    }

    /**
     * 编辑角色信息
     * @param $param
     */
    public function editRole($param)
    {
        try{

            $result =  $this->validate([
                'name'  => 'require|max:50',
                'level'   => 'in:1,2,3,4,5',
                'commission1'=>'>=:0|number',
                'commission2'=>'>=:0|number',
                'commission3'=>'>=:0|number'
            ],
                [
                    'name.require' => '名称必须',
                    'name.max'     => '名称最多不能超过50个字符',
                    'level'        => '等级必须在1到5之内',
                    'commission1'=>'推荐一级分拥不能为负数',
                    'commission2'=>'推荐二级分拥不能为负数',
                    'commission3'=>'下级代理分拥不能为负数',
                    'commission1.number'=>'推荐一级分拥格式不对',
                    'commission2.number'=>'推荐二级分拥格式不对',
                    'commission3.number'=>'下级代理分拥格式不对',
                ])->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑角色成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }





}