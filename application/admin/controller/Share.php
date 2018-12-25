<?php
namespace app\admin\controller;
use think\Db;
use app\admin\model\UserModel;

use \think\Controller;
use \think\Request;
use \think\Image;
use \think\Loader;

class Share extends Base
{
    public function index(){
        $where1 = [];
        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in',$str];
        }
        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();

        if(request()->isAjax()){
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];

            if(session('soretype')){
                $str = '';
                $soretypes = session('soretype');

                if (isset($param['searchText']) && !empty($param['searchText']) && in_array($param['searchText'],$soretypes) ) {
                    $where['a.sid'] = $param['searchText'];
                }else{
                    foreach ($soretypes as $value){
                        $str .= $value.',';
                    }
                    $str = rtrim($str, ',');
                    $where['a.sid'] = ['in',$str];
                }

            }else{
                if (isset($param['searchText']) && !empty($param['searchText'])) {
                    $where['a.sid'] = $param['searchText'];
                }
            }


            $list = Db::name('share')->alias('a')->join('ims_tpsoretype b','a.sid=b.id')->field('a.*,b.name')->where($where)->limit($offset,$limit)->select();

            $arr = array('禁用', '启用');
            foreach ($list as $key => $value) {

                    $operate = [
                                '编辑' => url('share/shareEdit', ['id' => $value['id']]),
                                '删除' => "javascript:shareDel('".$value['id']."')"
                    ];
                    $list[$key]['operate'] = showOperate($operate);
                    $list[$key]['pic'] = '<img src="/uploads/share/'.$value['pic'].'" style="width:50px">';
                    $list[$key]['status'] = $arr[$value['status']];



            }
            $return['total'] = Db::name('share')->alias('a')->where($where)->count();  //总数据
            $return['rows'] = $list;
            return json($return);            
        }
        $this->assign('soretype',$tpsoretype);
        return $this->fetch();
    }

    //获取个人二维码
    public function getqrcode($uid=1,$size=200){

        $url = 'http://www.wetoken.vip/wetoken/reg.html?uid='.$uid;
        // 引入 extend/qrcode.php
        Loader::import('phpqrcode', EXTEND_PATH);
        \QRcode::png($url,'./uploads/share/code.jpg','H',$size/43,1);
        // echo('<img src="/code.jpg" />');
        // return response(\QRcode::png($url), 200)->contentType("image/jpg");
    }

    //图片传
    public function uploadimg(){
	    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('file');
	    
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'share');
	        if($info){
	        	$res['code'] = 1;
	        	$res['data'] = $info->getSaveName();
	        	$res['msg'] = '上传成功';
	        	return json($res);
	        }else{
	        	$res['code'] = 0;
	        	$res['msg'] = '上传失败';
	        	$res['data'] = $info->getError();
	        	return json($res);
	        }
	    }    	
    }

    //添加模板
    public function shareadd(){

        $where1 = [];
        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in',$str];
        }
        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();

    	if(Request::instance()->isAjax()){

    		$type = input('type');
    		$d['title'] = input('title');
    		$d['pic'] = input('pic');
            $d['status'] = input('status');
            $d['sid'] = input('sid');

            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($d['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            //文字
    		for ($i=1; $i<6; $i++) {
	    		$data[$i]['text'] = input('text'.$i);
	    		$data[$i]['color'] = input('color'.$i)==null?'000000':input('color'.$i);
	    		$data[$i]['left'] = input('left'.$i)==null?0:input('left'.$i);
	    		$data[$i]['top'] = input('top'.$i)==null?0:input('top'.$i);
	    		$data[$i]['size'] = input('size'.$i)==null?0:input('size'.$i);
    		}  
            //二维码
            $code['size'] = input('size')==null?100:input('size');
            $code['left'] = input('left')==null?0:input('left');
            $code['top'] = input('top')==null?0:input('top');
            //头像
            $tx['size'] = input('tsize')==null?0:input('tsize');
            $tx['left'] = input('tleft')==null?0:input('tleft');
            $tx['top'] = input('ttop')==null?0:input('ttop');

            //检测模板
    		if(!$d['pic']){
    			$res['code'] = 0;
    			$res['msg'] = '请上传模板';
    			return json($res);
    		}

    		$image = Image::open('uploads/share/'.$d['pic']);

    		if($type==2){ //预览
                //设置头像
                // 调整大小并保存为thumb.png
                $thumb = \think\Image::open('./uploads/share/yyy.png');
                $thumb->thumb($tx['size'], $tx['size'])->save('./uploads/share/thumb.png');
                $image->water('./uploads/share/thumb.png',array($tx['left'],$tx['top']),100);

                for ($i=1; $i<6 ; $i++) { 
                    if($data[$i]['text']){
                        $image->text($data[$i]['text'],'static/admin/fonts/msyh.ttf',$data[$i]['size'],'#'.$data[$i]['color'],1,array($data[$i]['left'],$data[$i]['top']));
                    }
                }

                //设置二维码
                $this->getQRcode(1,$code['size']);

                $image->water('./uploads/share/code.jpg',array($code['left'],$code['top']),100);

    			$image->save('./uploads/share/tmp.jpg');
    			$res['code'] = 1;
    			$res['data'] = 'tmp.jpg?'.time();
    			return json($res);

    		}else{
                //检测数据
                if(!$d['title']){
                    $res['code'] = 0;
                    $res['msg'] = '请填写模板名称';
                    return json($res);                   
                }
                $c = db('share')->where(['title'=>$d['title']])->select();
                if($c){
                    $res['code'] = 0;
                    $res['msg'] = '模板名称已存在';
                    return json($res);
                }
                unset($data[1]['text']);
                $d['text1'] = json_encode($data[1]);
                $d['text2'] = json_encode($data[2]);
                $d['text3'] = json_encode($tx);
                $d['text4'] = json_encode($data[4]);
                $d['text5'] = json_encode($data[5]);
                $d['code'] = json_encode($code);
// return json($d);
                $r = db('share')->insert($d);
                if($r){
                    $res['code'] = 1;
                    $res['msg'] = '保存成功';
                    $this->log->addLog($this->logData,'进行了分享图片添加模板操作');
                    return json($res); 
                }else{
                    $res['code'] = 0;
                    $res['msg'] = '保存失败';
                    return json($res);                     
                }
            }
    		  		   		
    	}else{
    	    $this->assign('soretype',$tpsoretype);
    		return $this->fetch();
    	}
    }



    //编辑
    public function shareedit(){
        $where1 = [];
        if(session('soretype')){
            $str = '';
            $soretypes = session('soretype');
            foreach ($soretypes as $value){
                $str .= $value.',';
            }
            $str = rtrim($str, ',');
            $where1['id'] = ['in',$str];
        }
        $tpsoretype = Db::name('tpsoretype')->where($where1)->field('id,name')->select();

        $share = new UserModel();

        if(request()->isPost()){

            $type = input('type');
            $d['title'] = input('title');
            $d['pic'] = input('pic');
            $id = input('id');
            $d['status'] = input('status');

            $d['sid'] = input('sid');
            if(session('soretype')){
                $soretypes = session('soretype');
                if(!in_array($d['sid'],$soretypes)){
                    return $this->error('权限不足');
                }
            }

            for ($i=1; $i<6; $i++) { 
                $data[$i]['text']  = input('text'.$i);
                $data[$i]['color'] = input('color'.$i)==null?'000000':input('color'.$i);
                $data[$i]['left']  = input('left'.$i)==null?0:input('left'.$i);
                $data[$i]['top']   = input('top'.$i)==null?0:input('top'.$i);
                $data[$i]['size']  = input('size'.$i)==null?0:input('size'.$i);
            }  

            $code['size'] = input('size')==null?100:input('size'); 
            $code['left'] = input('left')==null?0:input('left'); 
            $code['top'] = input('top')==null?0:input('top');

            //头像
            $tx['size'] = input('tsize')==null?0:input('tsize');
            $tx['left'] = input('tleft')==null?0:input('tleft');
            $tx['top'] = input('ttop')==null?0:input('ttop');

            //检测模板
            if(!$d['pic']){
                $res['code'] = 0;
                $res['msg'] = '请上传模板';
                return json($res);
            }

            $image = Image::open('uploads/share/'.$d['pic']);

            if($type==2){ //预览
                //设置头像
                // 调整大小并保存为thumb.png
                $thumb = \think\Image::open('./uploads/share/yyy.png');
                $thumb->thumb($tx['size'], $tx['size'])->save('./uploads/share/thumb.png');
                $image->water('./uploads/share/thumb.png',array($tx['left'],$tx['top']),100);

                for ($i=1; $i<6 ; $i++) { 
                    if($data[$i]['text']){
                        $image->text($data[$i]['text'],'static/admin/fonts/msyh.ttf',$data[$i]['size'],'#'.$data[$i]['color'],1,array($data[$i]['left'],$data[$i]['top']));
                    }
                }

                //设置二维码
                $this->getQRcode(1,$code['size']);

                $image->water('./uploads/share/code.jpg',array($code['left'],$code['top']),100);

                $image->save('./uploads/share/tmp.jpg');
                $res['code'] = 1;
                $res['data'] = 'tmp.jpg?'.time();
                return json($res);

            }else{
                //检测数据
                // if(!$d['title']){
                //     $res['code'] = 0;
                //     $res['msg'] = '请填写模板名称';
                //     return json($res);                   
                // }
                $c = db('share')->where(['title'=>$d['title']])->select();
                // if($c){
                //     $res['code'] = 0;
                //     $res['msg'] = '模板名称已存在';
                //     return json($res);
                // }
                unset($data[1]['text']);
                $d['text1'] = json_encode($data[1]);
                $d['text2'] = json_encode($data[2]);
                $d['text3'] = json_encode($tx);
                $d['text4'] = json_encode($data[4]);
                $d['text5'] = json_encode($data[5]);
                $d['code'] = json_encode($code);
// return json($d);
                $r = db('share')->where(array('id' => $id))->update($d);
                if($r){
                    $res['code'] = 1;
                    $res['msg'] = '保存成功';
                    $this->log->addLog($this->logData,'进行了分享图片编辑模板操作');
                    return json($res);
                }else{
                    $res['code'] = 0;
                    $res['msg'] = '保存失败';
                    return json($res);                     
                }
            }
                            
        }

        $shareid = input('param.id'); 
        $oneshare= $share->getOneshare($shareid);      
        // var_dump($oneshare);exit;

        $oneshare['text1'] = json_decode($oneshare['text1'], true);
        $oneshare['text2'] = json_decode($oneshare['text2'], true);
        $oneshare['text3'] = json_decode($oneshare['text3'], true);
        $oneshare['text4'] = json_decode($oneshare['text4'], true);
        $oneshare['text5'] = json_decode($oneshare['text5'], true);
        $oneshare['code'] = json_decode($oneshare['code'], true);
        $this->assign('soretype',$tpsoretype);
        $this->assign('share', $oneshare);
        return $this->fetch();
    } 

    public function sharedel(){
        $id = input('param.id');

        $sid = Db::name('share')->where(['id'=>$id])->value('sid');
        if(session('soretype')){
            $soretypes = session('soretype');
            if(!in_array($sid,$soretypes)){
                return $this->error('权限不足');
            }
        }

        Db::name('share')->delete($id);
        $this->log->addLog($this->logData,'进行了分享图片删除模板操作');
        return json(['code' => 1,'msg' => '删除成功']);
    }

    //清空模板
    public function tempdel(){
        $role = new UserModel();
        $flag = $role->tempdel();
        $this->log->addLog($this->logData,'进行了分享图片清空模板操作');
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

    }

}