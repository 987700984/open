<?php
namespace kdniao;

class kdniao
{
    private $EBusinessID = '1366233'; //电商ID
    private $AppKey = 'be71e150-d91e-4c1f-8704-0e20c9ceac73'; //电商加密私钥，快递鸟提供，注意保管，不要泄漏
    private $ReqURL = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx'; //请求url

    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson($com , $order){
        $requestData['OrderCode'] = '';
        $requestData['ShipperCode'] = $com;
        $requestData['LogisticCode'] = $order;
        $requestData = json_encode($requestData);

        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost($this->ReqURL, $datas);

        $result = json_decode($result, true);
        if($result['State'] == 0){
            $res['msg'] = '暂无信息';
            $res['status'] = 0;
        }else{
            $res['msg'] = '查询成功';
            $res['status'] = 1;
            $res['data']['list'] = $result['Traces'];
        }
        return $res;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}