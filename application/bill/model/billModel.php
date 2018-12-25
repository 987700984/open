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
namespace app\bill\model;

use think\Model;

class billModel extends Model
{
	protected $table = 'ims_tpbill';

		/**
	 * 根据搜索条件获取币流水列表信息
	 * @param $where
	 * @param $offset
	 * @param $limit
	 */
	public function getbillByWhere($where, $offset, $limit)
	{
		$table = config('database.prefix');

		$sql = 'select b.id,b.ordersn,b.proc,b.type,b.payee,b.type2,s.name,b.uid,u.username,u.phone,b.content,b.sid,b.addtime,b.price,p.username as nickname from '.$table.'tpbill as b inner join '.$table.'tpuser as u on b.uid=u.id inner join '.$table.'tpsoretype as s on b.sid=s.id left join '.$table.'tpuser p on p.id = b.payee where 1=1 '.$where.'order by b.id desc limit '.$offset.','.$limit;

		// return $sql;
		//数据库导入
		// $this->getBillDemo();
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
	public function getBillDemo()
    {
        $user = $this->select();
        $demo = $this->connect('mysql://btcimmysql:DCpKPCJhxD@120.79.77.111:3306/btcimmysql#utf8');
        $member = $demo->table('lb_bill')->select();
        $id   = array();
        foreach ($user as $k => $v) {
            $id[] = $v['id']; 
        }
        $this->connect('mysql://open:MJZkS8dPj8iGxEAG@localhost:3306/open#utf8');
        foreach ($member as $key => $value) {
            if (!in_array($value['id'], $id)) {
                $arr = array(
                        'id' => $value['id'],                        
                        'sid' => $value['sid'],                        
                        'uid' => $value['uid'],                        
                        'addtime' => $value['addtime'],                        
                        'content' => $value['content'],                        
                        'type' => $value['type'],                        
                        'price' => $value['price'],                        
                        'type2' => $value['type2'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                        // 'id' => $value['id'],                        
                    );
                $this->table('ims_tpbill')->insert($arr);
            }
        }
        return $member;
    }


    /**
     * 根据搜索条件获取所有的币种
     * @param $where
     */
    public function getAllbill($where)
    {
		$table = config('database.prefix');

		$sql = 'select b.id from '.$table.'tpbill as b inner join '.$table.'tpuser as u on b.uid=u.id inner join '.$table.'tpsoretype as s on b.sid=s.id where 1=1 '.$where;

		$count = $this->query($sql);
		
		return count($count);

    }

    /**
     * 删除币流水信息
     * @param $id
     */
    public function delbill($id)
    {
    	try{
    
    		$this->where('id', $id)->delete();
    		return ['code' => 0, 'data' => '', 'msg' => '删除成功'];
    
    	}catch( PDOException $e){
    		return ['code' => 1, 'data' => '', 'msg' => $e->getMessage()];
    	}
    }    


}