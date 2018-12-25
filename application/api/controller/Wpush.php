<?php
namespace app\api\controller;

use think\Db;

class Wpush extends Base
{
    public function index(){
        $params = [
            'platform'=>"all",
            "audience"=>"all",
            "notification"=>[
                "android"=>[
                    "alert"=>"",
                    "extras"=>[
                        "extras"=>""
                    ]
                ],
                "ios"=>[
                    "alert"=>"",
                    "extras"=>[
                        "extras"=>""
                    ]
                ]
            ]
        ];

        $list = Db::name('news')->alias('a')
            ->join('ims_news_type b','a.identify=b.id')
            ->field('a.id,a.title,a.recommend,b.title as name')
            ->where(['recommend' => 1])->order('id')->select();

        if(!empty($list)){
            foreach ($list as $key=>$value){
                $params['notification']['android']['alert'] = $value['title'];
                $params['notification']['android']['extras']['extras'] = $value['name'].'-'.$value['id'];
                $params['notification']['ios']['alert'] = $value['title'];
                $params['notification']['ios']['extras']['extras'] = $value['name'].'-'.$value['id'];

                $res = $this->push($params);

                $res = json_decode($res,true);

                if($res['msg_id']){
                    Db::name('news')->where(['id'=>$value['id']])->update(['recommend'=>0]);
                    echo $value['title']."\n";
                }
            }
        }

    }

    private function push($params){

        $params = json_encode($params);

        $headers = array(
            "Content-type: application/json;charset='utf-8'",
            "Accept: application/json",
            "Authorization: Basic ".base64_encode("ff234d08109531341d2c79ca:1b7592b366dbfa31fd6327b2")
        );

        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, "https://api.jpush.cn/v3/push");

        $response = curl_exec($ch);
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}