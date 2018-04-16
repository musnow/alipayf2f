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
    'outTradeNo' => '5ad4bb4357d1f',             //商户订单号
//    'tradeNo' => 2018041621001004440200251927, //支付宝订单号

    //两个订单选项选其一进行撤销
];

$ret = $alipay->cancel($data);    //撤销订单
print_r($ret);                    //返回数据




/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_1/alipay.trade.cancel
{
"alipay_trade_cancel_response": {
"code": "10000",
"msg": "Success",
"action": "refund",
"out_trade_no": "5ad4bb4357d1f",
"retry_flag": "N",
"trade_no": "2018041621001004440200251928"
},
"sign": "iIbi4enIwUOPI2kpAf3Yh5U4V73xjhX060ADGDpdlR6lGaeeuLPbOOanLS7D78kl59hAFmBBxLxIxyLlbfn3IAiK3JqynsypCSo8TTfZqvfeddgyhZh9Ks/+UqGOyZcUhtqfeY7lnUbAk3STQ8QmZS3SpvOTxGI09qsBdP8qcAwNeWSnjEKH4t7N6RHyLnJZWu939m+olL7XxvR6oQkmjy9XC775PjE13ffXTAwS8qkFbMzl+2uxVuN2hYoV4F6AlS94TJq+AP0KM6AQD16/YyRJLshrgUEH3YqHW8OMLjC5LpRxvgM1zZSdbfRZ6ejCUMBRwPp9AJnOfstZEaEzCg=="
}
 */
