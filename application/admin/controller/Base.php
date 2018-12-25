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
namespace app\admin\controller;

use app\admin\extend\alipay\AlipayFundTransToaccountTransferRequest;
use app\admin\extend\alipay\AopClient;
use app\admin\model\Log;
use app\admin\model\Node;
use Qiniu\Auth;
use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {

        if(empty(session('username'))){

            $this->redirect(url('admin/login/index'));
        }

        //检测权限
        $control = lcfirst( request()->controller() );
        $action = lcfirst( request()->action() );
        //跳过登录系列的检测以及主页权限
        if(!in_array($control, ['login', 'index']) and $control . '/' . $action!='user/editpassword'){

            if(!in_array($control . '/' . $action, session('action'))){
                $this->error('没有权限');
            }
        }
        $this->logData=[
            'uid'=>session('id'),
            'name'=>session('username'),
            'aouth'=>session('role'),
            'ip'=>getIp(),
            'addtime'=>time(),

        ];
        $this->log=new Log();
        //获取权限菜单
        $node = new Node();
        $this->assign([
            'username' => session('username'),
            'pic' => session('pic'),
            'menu' => $node->getMenu(session('rule')),
            'rolename' => session('role')
        ]);

    }

    /*支付宝转账*/
    /**
     * @param $moneyinfo
     * @param $moneyinfo['oid']  订单号
     * @param $moneyinfo['money']  金额
     * @param $moneyinfo['zhifu_id']   支付宝账号（手机或邮箱）
     * @param $moneyinfo['zhifu_name']  支付宝真实姓名
     * *@param $moneyinfo['msg']  提示
     * @return array
     */

    protected function alipay($moneyinfo)
    {

        $aop = new AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2018051760142845';
        $aop->rsaPrivateKey = 'MIIEogIBAAKCAQEAw9cQNrMGD8/O31+d5t7YX8HukfKD2j1snjFj5o09OXPU8sbOybivgcujb2guOpbrujH0726LY43Oz3FLpTKoHMg0tnz+EvTb69dXd5Lxri6lpnVlpGATYdyqsH0VocprOVB3F35Uj4UgsGzDHnXEHw0o9iNP4p/cwA4OF+rkerD4VGzed5nfqx9JVzpa1l5G5FxKrhUz5uCalYEDHpZa2DyszwCshigWZbP+QOtE+MPNFfJhI1/Hopj2ciPggV7tQ1K3Z4ufg/4BN0BOCyuCbrYXB8RjiB3CijEJnhaoXERdN/hEl9OCveqTnEsqZ+xLOQQENNyr6h2RuLQXXsr7lQIDAQABAoIBAFCbkdnh4AncEBtTAOxJJyhq62Z5Opo2lCGc1LDNy7h9G9Z9zBmtgdfb2L5/VB/bhNVTwKxYhNkKQmiSCn/JlPab1U6TrgRhcq/lJ+RYwE9gdeBJC/gXb4LlUABqy9+XMIEbxJkP74BPXIAhlEJSWNIrGYQOTtBJ2pPWdSiVD0wMGML1Y1rNdFYU50xRRqmR82kqx9ba38XNx9vNp6h6gVsJDmjoApDBMqCmjF3Sodub4vX2PFlvgUzJaAJoMhkPdYV95d5cGLsvdNL31cCoeUmaXkyrEV7TUJ/p9E6wYZ+z+SCATOZJXvsQzSg7pGY+OqndyhXhsQ5yrnH8H76fQ+ECgYEA7wZ17BP7R3+oNF9G2vXUmMxANZCL5YwNhkSOeze3rtyy4GvVVMPnVeYlDjbGtXFPDEOoOlvyFSS5w/LKK9zwrP0mRhfryIZ/PDpyFDPoa8ussqe14wNVIptweqI9sXPGW1TXqERHZq5IxwC+AfHrZxtnWq+tfntBLyPAJybKkn0CgYEA0b98Xhem1xNgZ/MKDHBd02Fc/vCo2vJZs7f8483oqqiF4lkkwvaOWanE9zEXRJAuF76Hb8pwNIZK4qEO0pdWmvQ/51uoiqD7dkfbnCB8oOGBQU7uxpOtSdqNKDn+JPaxiBqEnYQ9Qw1sQMcVzWwBF1y6OY6jgoCjfe+bpyfUgPkCgYAbIpCgjGQqacOernJMyTupXQatDgvTs2KVq5LBSkIAB+4GrDc7uEG67rWmN3G3h3WB3uxqM6X34IN2S0nIUPzBpruBmZWa3inznG72/C2Wjzi7z25Gp0oy85KBWYnHa21JUQhqgdXZQk/gx6TKc7xVqDbDhM4dXcC4qUZXK4AzwQKBgAuwI/oKT13E0qZ4QKMYz+Grl0cNmhs5Tg5Zvlnja4e3BF7soMPMgXo1n6g2sBk9/5OLJnjwSvhiU2H1n6HUlRrlaXo2/VlcRyb8MhytIsTETDObdmrSZ+GpsBwBv0vIA3SWJbWxXMiTwuiJL4nW7uiiXi4+6JWpHXMzGvVhntHhAoGAZBjDoDTUBQKDJoxLcbyZhtMsf+2E3vesk3LXTTyM7MBH5lcq1YXARIUzai4j7okItXJQ0ryr3Sq22ExPJz+Nw2WFYbkGa9/foRKEuh46cBDCj03NnsMIW16972VTwHu1Lebvk4CFd/jSb+njBgTuJZ+NQby2sunZIJKnpAOfi7s=';
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqO4K0oAmrdTehM7IjSS5GZMSLJgLvPLgWGeHmRY3BCjSr+n45Xbg4FmVsoZkhSPz4ayanxeIdi1s26+bmk5nKoOolKyiEquLk0vyAEapfsOhMy/3gbbQ2piI2l8girZaVXsqTtLO/gkLpYUy6C24FDaoIgNZyMeOk46oGS3GdSsauKvSaJWAIsOpD4Dk1gIvTPQcY3/OHtQ+zUKKQ++aDDZGvQa5HSAPNB2rG3yGiaBYKydkl8bvHPk1setBx+2MaAqwZFkVaXFnzv8bEbFhtjGFBTZ+5xgo3PwiYwnCskGRg/asNj8XS3KIyRrKnDZ/On/IEYTh0uELXKRGbrUyGwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';
        $request = new AlipayFundTransToaccountTransferRequest();

        $data = [
            'out_biz_no' =>  $moneyinfo['oid'],
            'payee_type' => 'ALIPAY_LOGONID',
            'payee_account' => $moneyinfo['zhifu_id'],
            'payee_real_name'=>$moneyinfo['zhifu_name'],
//            'amount' => ($moneyinfo['amount']-$moneyinfo['poundage']).'',
            'amount' => $moneyinfo['money'].'',
            'remark' => $moneyinfo['msg'],
        ];
        $data = json_encode($data);
        $request->setBizContent($data);
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            return ['status' => 1, 'msg' => '转账成功'];
        } else {
            return ['status' => 0, 'msg' => '转账失败,' . $result->$responseNode->sub_msg];
        }
    }

    //七牛云图片
    public function uptoken(){
        $bucket = 'coin';
        $accessKey = 'kOL99lKkkjDNSrtdRg6TW10SqGf10b_YUiPbIQPJ';
        $secretKey = 'LoyHalelMRUcOOkZtl9tiisbsDPJNcXKkQK5iSpK';
        $auth = new Auth($accessKey, $secretKey);

        $policy = array(
           // 'returnUrl' => 'http://127.0.0.1/qiniudocs-master/demo/simpleuploader/fileinfo.php',
            'returnBody' => '{"scope":"coin:$(fname)"}',
        );

        $upToken = $auth->uploadToken($bucket, null, 3600);

        return json(['code'=>1,'msg'=>'获取成功','data'=>$upToken]);
    }

    /**
     * 导出excel
     * @param array $data 导入数据
     * @param string $savefile 导出excel文件名
     * @param array $fileheader excel的表头
     * @param string $sheetname sheet的标题名
     */
    protected function exportExcel($data, $savefile, $fileheader, $sheetname='Sheet1'){

        $excel = new \PHPExcel();
        if (is_null($savefile)) {
            $savefile = time();
        }else{
            //防止中文命名，下载时ie9及其他情况下的文件名称乱码
            iconv('UTF-8', 'GB2312', $savefile);
        }
        //设置excel属性
        $objActSheet = $excel->getActiveSheet();
        //根据有生成的excel多少列，$letter长度要大于等于这个值
        $letter = array('A','B','C','D','E','F','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        //设置当前的sheet
        $excel->setActiveSheetIndex(0);
        //设置sheet的name
        $objActSheet->setTitle($sheetname);
        //设置表头
        for($i = 0;$i < count($fileheader);$i++) {
            //单元宽度自适应,1.8.1版本phpexcel中文支持勉强可以，自适应后单独设置宽度无效
            //$objActSheet->getColumnDimension("$letter[$i]")->setAutoSize(true);
            //设置表头值，这里的setCellValue第二个参数不能使用iconv，否则excel中显示false
            $objActSheet->setCellValue("$letter[$i]1",$fileheader[$i]);
            //设置表头字体样式
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setName('微软雅黑');
            //设置表头字体大小
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setSize(12);
            //设置表头字体是否加粗
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setBold(true);
            //设置表头文字垂直居中
            $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置文字上下居中
            $objActSheet->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置表头外的文字垂直居中
            $excel->setActiveSheetIndex(0)->getStyle($letter[$i])->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //单独设置D列宽度为15
        $objActSheet->getColumnDimension('D')->setWidth(15);
        //这里$i初始值设置为2，$j初始值设置为0，自己体会原因
        for ($i = 2;$i <= count($data) + 1;$i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key=>$value) {
                //不是图片时将数据加入到excel，这里数据库存的图片字段是img
                if($key != 'img'){
                    $objActSheet->setCellValue($letter[$j].$i,$value);
                }
                //是图片是加入图片到excel
                if($key == 'pic'){
                    if($value != ''){
                        $value = iconv("UTF-8","GB2312",$value); //防止中文命名的文件
                        // 图片生成
                        $objDrawing[$key] = new \PHPExcel_Worksheet_Drawing();
                        // 图片地址
                        $objDrawing[$key]->setPath('.\Uploads'.$value);
                        // 设置图片宽度高度
                        $objDrawing[$key]->setHeight('80px'); //照片高度
                        $objDrawing[$key]->setWidth('80px'); //照片宽度
                        // 设置图片要插入的单元格
                        $objDrawing[$key]->setCoordinates('D'.$i);
                        // 图片偏移距离
                        $objDrawing[$key]->setOffsetX(12);
                        $objDrawing[$key]->setOffsetY(12);
                        //下边两行不知道对图片单元格的格式有什么作用，有知道的要告诉我哟^_^
                        //$objDrawing[$key]->getShadow()->setVisible(true);
                        //$objDrawing[$key]->getShadow()->setDirection(50);
                        $objDrawing[$key]->setWorksheet($objActSheet);
                    }
                }
                $j++;
            }
            //设置单元格高度，暂时没有找到统一设置高度方法
            $objActSheet->getRowDimension($i)->setRowHeight('80px');
        }

        header('Content-Type: application/vnd.ms-excel');
        //下载的excel文件名称，为Excel5，后缀为xls，不过影响似乎不大
        header('Content-Disposition: attachment;filename="' . $savefile . '.xls"');

        header('Cache-Control: max-age=0');
        // 用户下载excel
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');

        $objWriter->save('php://output');

        // 保存excel在服务器上
        //$objWriter = new PHPExcel_Writer_Excel2007($excel);
        //或者$objWriter = new PHPExcel_Writer_Excel5($excel);
        //$objWriter->save("保存的文件地址/".$savefile);

    }



}