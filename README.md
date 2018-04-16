# AlipayF2F
本项目是为支付宝当面付适配的扩展。

当面付是支付宝的一款支付产品，支持二维码和条码支付。   
但是官方提供的php demo使用了比较旧的框架封装，为了方便使用我将官方的demo抽离并封装成composer包方便使用；   
##使用依赖  
AlipayF2F 需要 PHP >= 5.5以上的版本，同时需要PHP安装以下扩展
```$xslt
- cUR extension
- openssl
```
##如何使用  
如果你想使用本项目请使用 composer 安装

```$xslt
$ composer require musnow/alipayf2f
```
或者在你的项目跟目录编辑 ```composer.json```
```$xslt
"require": {
    "musnow/alipayf2f": "^1.0.0"
}
```
更新
```$xslt
$ composer update
```
然后就可以愉快的使用了，更多示例查看 example
```$xslt
<?php
use \Musnow\AlipayF2F\Pay;

require '/vendor/autoload.php';

$config = [
    'appId'         => '',    //应用appid
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
```
##常见问题  
如果出现```curl: (60) SSL certificate problem```怎么办

php添加ssl ca证书链
在```https://curl.haxx.se/docs/caextract.html```下载最新的ca证书然后到php.ini里面添加路径。
```$xslt
curl.cainfo="/path/to/downloaded/cacert.pem"
# 绝对路径
```
or 在实例化时添加关闭ssl的参数
```$xslt
$config = [
    'ssl' => false
];
```

#License  
AlipayF2F is under the MIT license.
