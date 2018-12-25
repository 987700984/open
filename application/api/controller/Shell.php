<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 11:03
 */
namespace app\api\controller;

use app\api\model\UserModel;
use think\Controller;

class Shell extends Base {




    public function index(){

        set_time_limit(0);
        $user=new UserModel();

        $list=$user->whereOr('phone=rtid')->whereOr('phone=rtid2')->select();
        $a=0;
        foreach ( $list as $k=>$v){
            if($v['phone']!=0){

                $v->rtid=0;
                $v->rtid2=0;
                $status= $v->save();
                if($status){
                    $a++;
                }
            }
        }
        echo $a;

    }
}