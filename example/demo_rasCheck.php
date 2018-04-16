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

$ret = $alipay->rsaCheck($_REQUEST);  //notify数据验签
print_r($ret);                        //返回数据


