<?php
namespace app\api\controller;

use QL\QueryList;
use think\Cache;

class Feixiaohao extends Base
{
    private function getEx(){

        if(Cache::get('usd_exchange')){
            $ex = Cache::get('usd_exchange');
        }else{
            $url = 'http://web.juhe.cn:8080/finance/exchange/rmbquot?key=6133a45282999d98f4ae0d5044376c95';
            $html = file_get_contents($url);
            $res = json_decode($html,true);
            $ex = 6.8;
            if(!empty($res['result'][0])){
                foreach ($res['result'][0] as $key => $value){
                    if($value['name'] == '美元'){
                        $ex = $value['fBuyPri'];
                    }
                }
            }
            Cache::set('usd_exchange',$ex,86400);
        }
        return $ex;
    }

    /**
     * 设置行情
     */
    public function setlist($p=1){
        $url = 'https://www.feixiaohao.com/list_'.$p.'.html';
        $html = file_get_contents($url);
        $rules = array(
            //'id' => array('td:eq(0)','text'), //ID
            'title' =>array('.intro>a:eq(0)','text'), //key
            'content' => array('.intro>a:eq(1) ','text'), //名称

        );

        $data = QueryList::Query($html,$rules,'#table>tbody>tr')->data;

        if(!empty($data)){
            foreach ($data as $key => $value){
                $arr  = explode('-',$value['name']);
                $data[$key]['coin'] = $arr[0];
                $arr  = explode('/',$value['word']);
                $data[$key]['word'] = $arr[2];
            }
            for($i = 0;$i<10;$i++){
                Cache::set('FXH_'.$p.$i,array_slice($data,$i*10,10),300);
            }
            return '保存成功';
        }else{
            return '没有数据'.$url;
        }

    }


    /**
     * 设置行情
     */
    public function jinse($p=1){
        set_time_limit(0);
        $html = file_get_contents('http://www.xxsy.net/search?vip=0&sort=2&pn=1');

        $rules = array(
            //'id' => array('td:eq(0)','text'), //ID
            'title' =>['.info>h4>a','text'], //key
            'status'=>['.info>h4>.subtitle>span','text'],
            'cate'=>['.info>h4>span>a:eq(1)','text'],
            'href' => ['.info>h4>a','href'], //名称
            'writer'=>['.info>h4>span>a:eq(0)','text'],
            'wordcount'=>['.detail>.number>span:eq(3)','text','-i']
        );

        $rules_directory= array(
            //'id' => array('td:eq(0)','text'), //ID
            'title' =>['a:eq(0)','text'], //key
            'href'=>['a:eq(0)','href'],
        );

        $rules_content= array(
            //'id' => array('td:eq(0)','text'), //ID
            'content' =>['#auto-chapter','text'], //key
        );
        $data = QueryList::Query($html,$rules,'.search-result>.result-list>ul>li')->data;
        if(!empty($data)){
            foreach ($data as $key => $value){

                preg_match('/info\/(\d+)\.html/',$value['href'],$match);
                $url1='http://www.xxsy.net/partview/GetChapterList?noNeedBuy=1&special=0&maxFreeChapterId=0&bookid='.$match[1];
                $data[$key]['writer']=substr($value['writer'],3,strlen($value['writer'])-3);
                $html1= file_get_contents($url1);
//                dump($html2);die;
                $data[$key]['directory']=QueryList::Query($html1,$rules_directory,'#chapter>dd>ul>li')->data;
                foreach ($data[$key]['directory'] as $k=>$v){
                    $url_content= 'http://www.xxsy.net'.$v['href'];
                    $html_content= file_get_contents($url_content);
                    $data[$key]['directory'][$k]['content']=QueryList::Query($html_content,$rules_content,'.chapter-read')->data;
                    dump($data);die;
                }
            }
           dump($data);die;
        }else{

        }

    }





    /**
     * 获取行情
     */
    public function getlist(){
        $p = get_input_data('p',1);
        $page = floor($p/10)+1;
        $name = 'FXH_'.$page.($p-1)%10;
        $data = Cache::get($name);

        if(!$data){
            $res = $this->setlist($page);
            if($res === '保存成功'){
                $data = Cache::get($name);
            }else{
                $this->data['msg'] = '没有更多的数据了';
                return json($this->data);
            }

        }

        $this->data['status'] = 1;
        $this->data['msg'] = '获取成功';
        $this->data['data'] = $data;

        return json($this->data);

    }

    /**
     * 行情搜索
     */
    public function search(){
        $word = get_input_data('word');

        if(empty($word)){
            $this->data['msg'] = '关键词为空';
            return json($this->data);
        }

        $url = 'https://www.feixiaohao.com/search?word='.$word;
        $html = file_get_contents($url);
        $rules = array(
            //'id' => array('td:eq(0)','text'),       //ID
            'word' =>array('td:eq(1) a','href'),        //key
            'name' => array('td:eq(1)','text'),      //名称
            'pic' => array('td:eq(1) img','src'),    //图标
            'marketcap' => array('td:eq(2)','text'), //流通市值
            'price' =>array('td:eq(3)','text'),     //人民币价格
            'rank' => array('td:eq(4)','text')      //涨幅
        );

        $data = QueryList::Query($html,$rules,'#table>tbody>tr')->data;

        if(empty($data)){
            $this->data['msg'] = '未找到相关数据';

        }else{
            foreach ($data as $key => $value){
                $arr  = explode('/',$value['word']);
                $data[$key]['word'] = $arr[2];
            }

            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $data;
        }

        return json($this->data);
    }

    //获取ETH价格接口

    public function search_eth(){

        if(Cache::get('FXH_ETH_PRICE')){
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = ['price'=>Cache::get('FXH_ETH_PRICE')];
        }else{
            $url = 'https://www.feixiaohao.com/search?word=ETH';
            $html = file_get_contents($url);
            $rules = array(
                //'id' => array('td:eq(0)','text'),       //ID
                'word' =>array('td:eq(1) a','href'),        //key
                'name' => array('td:eq(1)','text'),      //名称
                'pic' => array('td:eq(1) img','src'),    //图标
                'marketcap' => array('td:eq(2)','text'), //流通市值
                'price' =>array('td:eq(3)','text'),     //人民币价格
                'rank' => array('td:eq(4)','text')      //涨幅
            );

            $data = QueryList::Query($html,$rules,'#table>tbody>tr')->data;

            if(empty($data)){
                $this->data['msg'] = '未找到相关数据';

            }else{
                $price=$data[0]['price'];
                $price=str_replace('￥','',$price);
                $price=str_replace(',','',$price);
//            $price=floatval($price);
                $this->data['status'] = 1;
                $this->data['msg'] = '获取成功';
                $this->data['data'] = ['price'=>$price];

                Cache::set('FXH_ETH_PRICE',$price,300);
            }
        }


        return json($this->data);
    }

    /**
     * K线图
     */
    public function line(){
        $word = get_input_data('word');
        $start = get_input_data('start');
        $end = get_input_data('end');
        $type = get_input_data('type');

        if(!in_array($type,['d','w','m','y'])){
            $this->data['msg'] = '未知类型';
            return json($this->data);
        }

        if(empty($word)){
            $this->data['msg'] = '缺少关键词';
            return json($this->data);
        }

        if(Cache::get('FXH_LINT_'.$word.$type)){
            $res = Cache::get('FXH_LINT_'.$word.$type);
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $res;

            return json($this->data);
        }else{
            $url = 'https://api.feixiaohao.com/coinhisdata/'.$word.'/'.$start.'000/'.$end.'000';
            $html = file_get_contents($url);
            $res = json_decode($html,true);

            $ex = $this->getEx();
            if(!empty($res['price_usd'])){
                $max=$min =$res['price_usd'][0][1];
                foreach ($res['price_usd'] as $key => $value){
                    //最大值
                    if($max < $value[1]){
                        $max = $value;
                    }
                    //最小值
                    if($min > $value[1]){
                        $min = $value;
                    }

                    //人民币
                    $res['price_cny'][$key] = $value;
                    $res['price_cny'][$key][1] = sprintf("%.2f", $value[1]*$ex/100);


                }
                $res['open_usd'] = $res['price_usd'][0];
                $res['close_usd'] = end($res['price_usd']);
                $res['high_usd'] = $max;
                $res['low_usd'] = $min;

                $res['open_cny'] = [$res['open_usd'][0],sprintf("%.2f", $res['open_usd'][1]*$ex/100)];
                $res['close_cny'] = [$res['close_usd'][0],sprintf("%.2f", $res['close_usd'][1]*$ex/100)];
                $res['high_cny'] = [$res['high_usd'][0],sprintf("%.2f", $res['high_usd'][1]*$ex/100)];
                $res['low_cny'] = [$res['low_usd'][0],sprintf("%.2f", $res['low_usd'][1]*$ex/100)];
            }else{
                $this->data['msg'] = '未找到相关数据';
                return json($this->data);
            }
            Cache::set('FXH_LINT_'.$word.$type,$res,300);
            $this->data['status'] = 1;
            $this->data['msg'] = '获取成功';
            $this->data['data'] = $res;

            return json($this->data);

        }
    }

    public function cache(){
        set_time_limit(0);
        $head = 'http://open.demo.168erp.cn';
        $urls = array(
            $head.'/api/feixiaohao/setlist?p=1',
            $head.'/api/feixiaohao/setlist?p=2',
        );
        curls($urls);
        $url = $head.'/api/feixiaohao/getlist';
        $res = mycurl($url);
        $res = json_decode($res,true);
        $list = array();
        if(is_array($res['data'])){
            foreach ($res['data'] as $key => $value){
                $list[] = $head.'/api/feixiaohao/line?type=d&word='.$value['word'].'&start='.(time()-86400).'&end='.time();
            }
        }
        dump($list);
        curls($list);
    }


}
