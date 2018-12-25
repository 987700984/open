<?php
namespace app\shop\model;

use think\Model;

class TpgoodsPrice extends Model
{
    public function get_key_name($type=1){
        $key=explode(',',$this->key);
        if($type==1){
            $list=[];
            foreach ($key as $k=>$v){
                $a=TpspecItem::get($v);
                $list[]=$a['item_name'];
            }

        }else{
            $list='';
            foreach ($key as $k=>$v){
                $a=TpspecItem::get($v);
                if($k!=0){
                    $list.=','.$a['item_name'];
                }else{
                    $list.=$a['item_name'];
                }
            }
        }
        return $list;
    }
}