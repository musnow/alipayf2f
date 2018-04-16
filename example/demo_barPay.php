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
    'outTradeNo' => uniqid(),         //商户订单号，需要保证唯一
    'totalFee' => 0.01,               //订单金额，单位 元
    'orderName' => '测试订单',          //订单标题
    'authCode' => 285507219056550776,  //用户付款码，25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准

    //authCode也就是用户支付宝上的付款码 《支付宝->付钱->条形码信息（查看数字）》
];

$ret = $alipay->barPay($data);     //条码支付

print_r($ret);                    //返回数据




/*
 * 返回数据示例
 * 支付宝文档：https://docs.open.alipay.com/api_1/alipay.trade.pay
Array
(
    [alipay_trade_pay_response] => Array
        (
            [code] => 10000
            [msg] => Success
            [buyer_logon_id] => igc***@sandbox.com
            [buyer_pay_amount] => 0.01
            [buyer_user_id] => 2088102168936442
            [buyer_user_type] => PRIVATE
            [fund_bill_list] => Array
                (
                    [0] => Array
                        (
                            [amount] => 0.01
                            [fund_channel] => ALIPAYACCOUNT
                        )

                )

            [gmt_payment] => 2018-04-16 22:44:02
            [invoice_amount] => 0.01
            [out_trade_no] => 5ad4b6b097641
            [point_amount] => 0.00
            [receipt_amount] => 0.01
            [total_amount] => 0.01
            [trade_no] => 2018041621001004440200251745
        )

    [sign] => Jj9Jc9Eo2c9mxj71xAOBKQxEictPXzRzEvnyGt7crWWYsv9iF0nt1laLMqAkCOs/s4ne7Hdvyo/PtvBqbI5tFY5uwD3dYL5ZEdby+xfi0PNf5erg2+WosvTl2dkKU32NXbT1Thuy441Xm+eknznfVYg17kY7B+MG+ZrUre4yycbhz5/mRnGRfONTLguQyAzCmBiRPUDeGTeEJhi7qesgtv45nRthsKm5Z9jdReql6YdFv8Ow+wishJWviGDmqr211lgyzwenPS1Af+Zf+FUHYiNolznv89TLMdFpYTBPMyD/qATE65357ZS39kBWKE76ddEjdQXA8J6w+FxyOZPh8A==
)
 */
