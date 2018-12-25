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
namespace app\exchange\model;

use think\Db;
use think\Model;

class exchangeModel extends Model
{
	protected $table = 'ims_tpexchange';

		/**
	 * 根据搜索条件获取币流水列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getexchangeByWhere($where, $offset, $limit)
	{
		$table = config('database.prefix');

		$sql = 'select e.*,s.name,u.username,u.phone from '.$table.'tpexchange as e inner join '.$table.'tpuser as u on e.uid=u.id inner join '.$table.'tpsoretype as s on e.sid=s.id  where 1=1 '.$where.' order by e.stutas,e.id desc  limit '.$offset.','.$limit;

		// return $sql;
        //数据库导入
        // $this->getExchangeDemo();
		return $this->query($sql);

		// return $this->alias('b')
		// 		->join('tpuser u', 'b.uid = u.id')
		// 		->join('tpsoretype s', 'b.sid = sid')
		// 		->where($where)
		// 		->field("b.id,s.name,b.uid,u.nickname,u.phone,b.content,b.sid,b.addtime,b.price")
		// 		->limit($offset, $limit)
		// 		->select();
	}

        //数据库导入
    public function getExchangeDemo()
    {
        $user = $this->select();
        $demo = $this->connect('mysql://btcimmysql:DCpKPCJhxD@120.79.77.111:3306/btcimmysql#utf8');
        $member = $demo->table('lb_exchange')->select();
        $id   = array();
        foreach ($user as $k => $v) {
            $id[] = $v['id']; 
        }
        $this->connect('mysql://open:MJZkS8dPj8iGxEAG@localhost:3306/open#utf8');
        foreach ($member as $key => $value) {
            if (!in_array($value['id'], $id)) {
                $arr = array(
                        'id' => $value['id'],                        
                        'uid' => $value['uid'],                        
                        'address' => $value['address'],                        
                        'price' => $value['price'],                        
                        'status' => $value['status'],                        
                        'addtime' => $value['addtime'],                        
                        'sid' => $value['sid'],                        
                        'content' => $value['type2'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                    );
                $this->table('ims_tpexchange')->insert($arr);
            }
        }
        return $member;
    }

    /**
     * 根据搜索条件获取所有的币种
     * @param $where
     */
    public function getAllsoretype($where)
    {
		$table = config('database.prefix');

		$sql = 'select e.*,s.name,u.username,u.phone from '.$table.'tpexchange as e inner join '.$table.'tpuser as u on e.uid=u.id inner join '.$table.'tpsoretype as s on e.sid=s.id where 1=1 '.$where;

		$count = $this->query($sql);
		
		return count($count);

    }

    /**
     * 取消币流水信息
     * @param $id
     */
    public function delexchange($id)
    {
    	try{
    	    $ex = Db::name('tpexchange')->where(['id'=>$id])->find();
            //改变状态
            $this->save(array('stutas' => 0), array('id' => $id));

            //退款
            $soreType = Db::name('tpsoretype')->field('id,name,status,exchange')->where(array('id' => $ex['sid']))->find();
            $res = Db::name('tpintegral')
                ->where('uid='.$ex['uid'].' AND sid='.$ex['sid'])
                ->update(['integral'=>['exp','integral+'.$soreType['exchange']*$ex['price']],'usable'=>['exp','usable+'.$soreType['exchange']*$ex['price']]]);
    		return ['code' => 0, 'data' => '', 'msg' => '取消成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }    

    /**
     * 更新币流水信息
     * @param $id
     */
    public function saveexchange($id)
    {
    	try{
    
    		$this->save(array('stutas' => 1), array('id' => $id));
    		return ['code' => 0, 'data' => '', 'msg' => '更新成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }  
}