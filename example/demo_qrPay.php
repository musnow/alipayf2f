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
    'outTradeNo' => uniqid(),     //商户订单号，需要保证唯一
    'totalFee' => 0.01,           //订单金额，单位 元
    'orderName' => '测试订单',      //订单标题
];

$ret = $alipay->qrPay($data);     //扫码支付

print_r($ret);                    //返回数据



/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_1/alipay.trade.precreate
Array
(
    [alipay_trade_precreate_response] => Array
        (
            [code] => 10000
            [msg] => Success
            [out_trade_no] => 5ad4b4be152fc
            [qr_code] => https://qr.alipay.com/bax03269eewcqvbfdx40003c
        )

    [sign] => NaJOhWuoMkRPqRaE+tR+IaeuCaq8LsOi4D2JE+l/jH1ovuI+3Z8WioKfQugetFUqQFL+LBL9Dv3cI3HmqWiW2PwILfZC3bGk7BMEn1sXmS8QSUOyOUKtgt41ryottODrRErU0brpRLTXpOQ6Kksqxr7wYUBA/MZWFYkFTdUJFYUvSheL00sC5ClqwqXXETQRHhmY8u0MBs7NoNJyeculCsI/mBMsz0rHgtuXkQZ3flqWf9fLCVglPq3idBcxW5YdFv6PeAJ6qqFVA5Hkj3kicu2jOQowRU7CvotAFtpKBF6TfU/WwN+n4vWkJLekGxoRn7WVMP1kKYDG1uqI8HLOOA==
)
 */
