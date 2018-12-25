<?php
// +---------------------------------------------------------------------+
// | BTX        | [ WE CAN DO IT JUST THINK ]                        	 |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | ALong <9822533@qq.com>                               	 |
// +---------------------------------------------------------------------+
// | Repository | http://119.23.43.34:12345/zzx_zc/cash_flow.git         |
// +---------------------------------------------------------------------+

namespace app\api\controller;

use think\Db;
use think\Queue;

class MessageQueue extends Base
{
    public function ore(){
        //去掉过期的矿机
        Db::name('shop_orderform')->where(['zt' => 1])->where('uptime','<',time())->update(['zt'=>2]);
        $price  =  db::name('market')->where(['coin' => 'btjz'])->order('id desc')->value('price'); //币价格
        $miner = Db::name('miner')->where(['sid'=>7])->find();//配置参数
        $kjsl      = db::name('shop_orderform')->where('zt=1 and sid=7')->sum('kjsl'); //全部矿机算力
        $quanwang = $kjsl+$miner['force']+$price*100;
        $chanbi = ($kjsl*2.5+2500000)/1846/$price;  //每小时产币

//        echo '行情'.$price.'<br/>';
//        echo '全网算力'.$quanwang.'<br/>';
//        echo '用户总算力'.$kjsl.'<br/>';
//        echo '每小时产币'.$chanbi.'<br/>';

        $shop = Db::name('shop_orderform')->field('goodsid,uid,sum(kjsl) as kjsl')->group('uid')->where(['zt' => 1])->select();

        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\api\job\Ore';
        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName  = "oreJobQueue";

        foreach ($shop as $key => $value) {

            $res = floor($chanbi*$value['kjsl']/$quanwang*10000)/10000;

            // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
            //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
            $jobData       	  = [ 'ts' => time(), 'uid' =>  $value['uid'], 'sid' => 7 ,'num' => $res , 'kjsl' => $value['kjsl'] ] ;
            // 4.将该任务推送到消息队列，等待对应的消费者去执行
            $isPushed = Queue::push( $jobHandlerClassName , $jobData , $jobQueueName );
            // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
            if( $isPushed !== false ){
                echo 'success : uid='.$value['uid'].' num='.$res.' kjsl='.$value['kjsl']."\n";
            }else{
                echo 'error : uid='.$value['uid'].' num='.$res.' kjsl='.$value['kjsl']."\n";
            }
        }
    }


}