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
    'outTradeNo' => '5ad4b6b097641',             //商户订单号，需要保证唯一
    //'tradeNo' => 2018041621001004440200251745, //支付宝订单号
    //两个订单选项选其一进行查询

    'refundAmount' => 0.01,                      //需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
    'refundReason' => '测试退款',                 //退款的原因说明
];

$ret = $alipay->refund($data);    //订单退款

print_r($ret);                    //返回数据




/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_1/alipay.trade.refund
{
"alipay_trade_refund_response": {
"code": "10000",
"msg": "Success",
"buyer_logon_id": "igc***@sandbox.com",
"buyer_user_id": "2088102168936442",
"fund_change": "Y",
"gmt_refund_pay": "2018-04-16 22:57:34",
"out_trade_no": "5ad4b6b097641",
"refund_detail_item_list": [
{
"amount": "0.01",
"fund_channel": "ALIPAYACCOUNT"
}
],
"refund_fee": "0.01",
"send_back_fee": "0.01",
"trade_no": "2018041621001004440200251745"
},
"sign": "MviX3hDe2t5Bvu9sMHJeCjsLWyy9rTzOefJDW47yZ0K3bjXoVIGuykShjBHJppywOcbQuGMKIa1JImoi3+55QL44Pwx42bPO1a6AJwFAbIT95wh4215E49eXNSQSy/lFgKHDq8vMh/cSjlzo4ebIa5qZvYSkD10O03+zKS/ZE9+O2QtNPQAas4rjdJnR6TYBtY81ADBn0ZQqpLGnFnL+Pqc7XePmVMwKBeUYPCp7TP80RXQDmNp/UgWnD3etIgWPlqlNbIZ/zNBFmAXN2kKH/gwUwExaP798Rgkh3jS2ffPYxDT2a6fWs7rDFJcXjMEAcqyOc3dkHwHN0Y9woHxLbg=="
}
 */
