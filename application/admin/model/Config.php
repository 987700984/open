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

class Config extends Model
{

    /**
     * 插入配置信息
     * @param $param
     */
    public function insertRole($param)
    {
        try{

            $result =  $this->validate( [
                'name'  => 'require|max:50',
                'key'=>'uniqu'
            ],
                [
                    'name.require' => '名称必须',
                    'name.max'     => '名称最多不能超过25个字符',
                    'key.uniqu'     => '变量已存在',
                ])->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加配置成功'];
            }
        }catch( PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * 编辑配置信息
     * @param $param
     */
    public function editRole($param)
    {
        try{

            $result =  $this->validate([
                'name'  => 'require|max:50',
            ],
                [
                    'name.require' => '名称必须',
                    'name.max'     => '名称最多不能超过50个字符',
                ])->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑配置成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }





}