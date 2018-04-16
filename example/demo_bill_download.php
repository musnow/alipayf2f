<?php
/**
 * Created by PhpStorm.
 * User: lixia
 * Date: 2018/4/16
 * Time: 21:23
 */

use \Musnow\AlipayF2F\Pay;

require_once '../vendor/autoload.php';

/*
 * 支付宝当面付配置信息
 * 新建应用：https://openhome.alipay.com/platform/appManage.htm
 */
$config = [
    'appId'         => '',                                           //应用appid
    'notifyUrl'     => 'https://www.baidu.com',                      //异步通知地址

    //应用私钥
    // ！！！注意：如果是文件方式，文件中只保留字符串，不要留下 -----BEGIN RSA PRIVATE KEY----- 这种标记
    'rsaPrivateKey' => ''
];


$alipay = new Pay($config);

$data = [
    'billType' => 'trade',          //账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
    'billDate' => '2018-04-15',   //账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。

    //账单日期需要是结算后的日期，不能是今天，可以是昨天前天
];

$ret = $alipay->billDownload($data);    //撤销订单
print_r($ret);                    //返回数据




/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_15/alipay.data.dataservice.bill.downloadurl.query
{
"alipay_data_dataservice_bill_downloadurl_query_response": {
"code": "10000",
"msg": "Success",
"bill_download_url": "http://dwbillcenter.alipaydev.com/downloadBillFile.resource?bizType=trade&userId=20881021688175720156&fileType=csv.zip&bizDates=20180415&downloadFileName=20881021688175720156_20180415.csv.zip&fileId=%2Ftrade%2F20881021688175720156%2F20180415.csv.zip×tamp=1523890896&token=b5365bba6421a53452dae903e0eec436"
},
"sign": "C+wJbyQqvLzZPdAqHiIIS75uYR21+7Bf9H7xqp1SlM+JvC3TEgauz9KyPHWFThwGIVMBqJdJhu7OIjBrCKVdJu3zDuLwYVVoPAOBuOaiYyDsX23+cyrTsCrkdpqQWPX0fTrdCWy2xsREJjhzXNRy93YnCx/u/wxMNY4daR8F0g2RccJE++Pgh5UW7wBs6XJXDKRBigkOxSu2Wo/fOPhxpb4aUuGj3qeYLaUZYnooCar7LTG9/MtQ2Rh01O4mt/+CwK8JAgyQFiE9fz8nfcmikWW35hQ+xEkwrMgQ2sTU5vCaDCidolX6sIZyz3bPsbzgXKyDCEq9jPsKi+DH/RCv/w=="
}
 */
