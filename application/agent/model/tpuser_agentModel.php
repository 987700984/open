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
namespace app\agent\model;

use think\Model;

class tpuser_agentModel extends Model
{

    protected $table = 'ims_tpagent_apply';


    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function gettpuseragentByWhere($where, $offset, $limit)
    {

        return $this->where($where)->limit($offset, $limit)->order('status asc,addtime desc')->select();

    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAlltpuseragent($where)
    {
        return $this->where($where)->count();
    }


    public function soretype(){
        return db('tpsoretype')->where('status=1')->select();
    }

    public function insertagent($param){
        $id = $param['id'];
        if ($id) {
            $apply = db('tpagent_apply')->where('id = '.$id)->field('username, password,phone')->find();
            $apply['addtime'] = time();
            
            if ($param['typeid']) {
                $apply['typeid']  = $param['typeid'];
            }
            if ($param['level']) {
                $apply['level']   = serialize($param['level']);
            }
            // var_dump($apply);exit;
            $this->where('id='.$id)->update(['status' => 1]);
            return db('tpagent')->insert($apply);

        }
    }

    public function headpic($picid){
        $pic = unserialize($picid);
        foreach ($pic as $key => $value) {
            $res = db('tppic')->where('type=1 and id='.$value)->find();
            // var_dump($res);exit;
            if ($res) {
                return "<img style='width:60px;' src='".$res['pic']."'>";
            }
        }
    }



}