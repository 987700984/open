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

/**
 * 文件路径： \application\index\job\Hello.php
 * 这是一个消费者类，用于处理 helloJobQueue 队列中的任务
 */
namespace app\api\job;

use think\Db;
use think\queue\Job;

class Ore {

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        // 如有必要,可以根据业务需求和数据库中的最新数据,判断该任务是否仍有必要执行.
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }

        $isJobDone = $this->doOreJob($data);

        if ($isJobDone) {
            //如果任务执行成功， 记得删除任务
            $job->delete();
            print('success : uid='.$data['uid'].' num='.$data['num'].' kjsl='.$data['kjsl']);
        }else{
            if ($job->attempts() > 10) {
                //通过这个方法可以检查这个任务已经重试了几次了
                print('error : uid='.$data['uid'].' num='.$data['num'].' kjsl='.$data['kjsl']);
                $job->delete();
                // 也可以重新发布这个任务
                //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
                //$job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
            }
        }
    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){
        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function doOreJob($data) {
        // 根据消息中的数据进行实际的业务处理...

        // 启动事务
        Db::startTrans();
        try{

            $integral = Db::name('tpintegral')->where(['uid' => $data['uid'],'sid'=>7])->find();
            Db::name('tpintegral')->where(['uid' => $data['uid'], 'sid' => $data['sid']])
                ->update(['money' => $integral['money']+$data['num'], 'dug_money' => $integral['dug_money']+$data['num'], 'integral' => $integral['integral']+$data['num']]);

            //echo $value['uid'].'算力'.$value['kjsl'].'产币'.$res.'个<br/>';
            $arr = array(
                'kjsl' => $data['kjsl'],
                'addtime' => $data['ts'],
                'uid' => $data['uid'],
                'money' => $data['num'],
            );

            Db::name('shop_log')->insert($arr);

            // 提交事务
            Db::commit();

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            print ($e->getMessage());
            return false;
        }

    }
}