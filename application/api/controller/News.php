<?php

namespace app\api\controller;

use think\Request;


use think\Db;



class News extends Base{
		//新闻列表

	public function getList(){

		$p = get_input_data('p',1);

		$row = get_input_data('row',20);		

		$data['list'] = db::name('news')->field('id,title,pic,reviews,addtime')->where(['identify'=>8,'addtime'=>['<',time()]])->order('addtime desc')->limit(($p-1)*$row.','.$row)->select();
		$data['allpage'] = db::name('news')->where(['identify'=>8,'addtime'=>['<',time()]])->count();
		
		foreach ($data['list'] as $key => $value) {

		    $file = $value['pic'];

		    if(isset($file)) $file =  array();

			$data['list'][$key]['file'] = $file;

		}



		$this->data['status'] = 1;

		$this->data['msg'] = '获取成功';

		$this->data['data'] = $data;			

		return json($this->data);			

	}

	/**
	 * 新闻详情
	 * @return [type] [description]
	 */
	public function getNews(){

		$id = get_input_data('id');

		if(empty($id)){

			$this->data['msg'] = 'ID为空';			

			return json($this->data);	

		}

		$result = db::name('news')->field('id,title,content,addtime')->where('id='.$id)->find();

		$reviews = null;

		if($result){

			$reviews = db::name('reviews')->alias('r')->join('ims_tpuser u', 'r.uid=u.id')

				->field('r.uid,r.content,r.addtime,u.username,u.pic')

				->where('r.post_id='.$id)

				->order('r.addtime desc')

				->select();			

		}

		$data['news'] = $result;

		$data['reviews'] = $reviews;

		$this->data['status'] = 1;

		$this->data['msg'] = '获取成功';

		$this->data['data'] = $data;			

		return json($this->data);				

	}

	public function reviews(){

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

	}

    /**
     *
     */
	public function index(){
        $id = get_input_data('id');

        if(empty($id)){
            return $this->fetch('error');
        }

        $result = db::name('news')->field('id,title,content,addtime')->where('id='.$id)->find();

        if($result){
            $this->assign('news',$result);
            return $this->fetch();
        }else{
            return $this->fetch('error');
        }

    }

    /**
     * 糖果帮助中心列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHelp(){
	    $sid = get_input_data('sid');


	    $data['list'] = Db::name('tphelp')->where(['sid'=>$sid])->order('des ,id desc')->select();
        $data['total'] = Db::name('tphelp')->where(['sid'=>$sid])->count();
        $this->data['status'] = 1;
        $this->data['msg'] = '获取成功';
        $this->data['data'] = $data;
        return json($this->data);
    }

    /**
     * 帮助详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHelpDet(){
        $id = get_input_data('id/d');

        $data = Db::name('tphelp')->where(['id'=>$id])->find();

        if($data){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $data;
            return json($this->data);
        }else{
            $this->data['msg'] = '文章已删除';
            return json($this->data);
        }

    }

}
