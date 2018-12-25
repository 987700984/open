<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class Notice extends Base{
		//公告列表

	public function getList(){

		$p = get_input_data('p',1);

		$row = get_input_data('row',20);		

		$data['list'] = db::name('notice')->field('id,title,addtime')->where(['is_personal'=>0])->order('addtime desc')->limit(($p-1)*$row.','.$row)->select();
		$data['allpage'] = db::name('notice')->count();
		
		$this->data['status'] = 1;

		$this->data['msg'] = '获取成功';

		$this->data['data'] = $data;			

		return json($this->data);			

	}

    public function getWetokenList(){



        $data['list'] = db::name('news')->field('id,title,addtime')->where('identify',9)->order('addtime desc')->select();
        $data['allpage'] = db::name('news')->where('identify',9)->count();

        $this->data['status'] = 1;

        $this->data['msg'] = '获取成功';

         $this->data['data'] = $data;

        return json($this->data);

    }

	/**
	 * 公告详情
	 * @return [type] [description]
	 */
	public function getNotice(){

		$id = get_input_data('id');

		if(empty($id)){

			$this->data['msg'] = 'ID为空';			

			return json($this->data);	

		}

		$result = db::name('notice')->field('id,title,content,addtime')->where('id='.$id)->find();

		$reviews = null;

		/*if($result){

			$reviews = db::name('reviews')->alias('r')->join('ims_tpuser u', 'r.uid=u.id')

				->field('r.uid,r.content,r.addtime,u.username,u.pic')

				->where('r.post_id='.$id)

				->order('r.addtime desc')

				->select();			

		}

		$data['notice'] = $result;*/

		$data['reviews'] = $reviews;

		$this->data['status'] = 1;

		$this->data['msg'] = '获取成功';

		$this->data['data'] = $data;			

		return json($this->data);				

	}



	/*public function reviews(){

		$data['post_id'] =intval(get_input_data('id'));

		$data['content'] = get_input_data('content');

		if(empty($data['post_id'])){

			$this->data['msg'] = 'ID为空';			

			return json($this->data);	

		}

		if(empty($data['content'])){

			$this->data['msg'] = '内容为空';			

			return json($this->data);	

		}

		$data['addtime'] = time();

		$data['uid'] = session('user.id');

		$result = db::name('reviews')->add($data);

		if($result){
			db::name('post')->where('id='.$data['post_id'])->setInc('reviews');
			$this->data['status'] = 1;

			$this->data['msg'] = '添加成功';			

			return json($this->data);	

		}

			$this->data['msg'] = '添加失败';			

			return json($this->data);			

	}	*/

}
