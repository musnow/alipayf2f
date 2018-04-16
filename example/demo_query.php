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
    'outTradeNo' => '5ad4b6b097641',             //商户订单号
    //'tradeNo' => 2018041621001004440200251745, //支付宝订单号

    //两个订单选项选其一进行查询
];

$ret = $alipay->query($data);     //查询订单

print_r($ret);                    //返回数据




/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_1/alipay.trade.cancel
{
"alipay_trade_query_response": {
"code": "10000",
"msg": "Success",
"buyer_logon_id": "igc***@sandbox.com",
"buyer_pay_amount": "0.01",
"buyer_user_id": "2088102168936442",
"buyer_user_type": "PRIVATE",
"fund_bill_list": [
{
"amount": "0.01",
"fund_channel": "ALIPAYACCOUNT"
}
],
"invoice_amount": "0.01",
"out_trade_no": "5ad4b6b097641",
"point_amount": "0.00",
"receipt_amount": "0.01",
"send_pay_date": "2018-04-16 22:44:01",
"total_amount": "0.01",
"trade_no": "2018041621001004440200251745",
"trade_status": "TRADE_SUCCESS"
},
"sign": "Avhv/cq0bTZM6EY4EHdhLsosHXSMqshAKX/6O6L8SCcFNybgoq5/zHWBNIUR7ftfdIeugyfLgF0LN33gRsC2CCs1RrHAbDRSKJMycyPX7i9l9GXi4lRQMb4HcxYp4zQtIkv07So8m1IYxbSnh9GKtz/71uFNuWOgkMncR6UijSqrPGBqwHwTo1EKM1QmEYurwvsYOzhsgyb7qP3pNgrHb1wgAEFXjAQtGrCf5OoG/mzNgGpFuRAejT+J69VcQHV/FcPkKa8jpKzSuoAMGCZC0OcgY33kkIyblQL6n5Pt792Gp49GQ+yoa0WRWdmmmV8OyFBn9+rR5awK8WhvY5epRA=="
}
 */
