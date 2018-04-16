<?php

namespace Musnow\AlipayF2F;

class Pay
{
    protected $ssl = true;
    protected $sign_type = 'RSA2';
    protected $charset = 'UTF-8';
    protected $requestUrl = 'https://openapi.alipay.com/gateway.do';
    protected $appId;
    protected $rsaPrivateKey;
    protected $notifyUrl;

    public function __construct($config = null)
    {
        if (!is_array($config)) {
            return false;
        }

        foreach ($config as $key => $val) {
            if (isset($key)) {
                $this->$key = $val;
            }
        }
    }

    /*
    * 扫码支付
    * 收银员通过收银台或商户后台调用支付宝接口，生成二维码后，展示给用户，由用户扫描二维码完成订单支付。
    */
    public function qrPay($data = array())
    {
        $request = [
            'type' => 'alipay.trade.precreate',
            'reqs' => [
                'out_trade_no' => $data['outTradeNo'], //订单号，需要保证唯一
                'total_amount' => $data['totalFee'], //单位 元
                'subject' => $data['orderName'],  //订单标题
            ]
        ];

        $result = $this->curlPost($this->merge($request));
        return json_decode($result, true);
    }

    /*
    * 条码支付
    * 收银员使用扫码设备读取用户手机支付宝“付款码”/声波获取设备（如麦克风）读取用户手机支付宝的声波信息后，将二维码或条码信息/声波信息通过本接口上送至支付宝发起支付。
    */
    public function barPay($data = array())
    {
        $request = [
            'type' => 'alipay.trade.pay',
            'reqs' => [
                'out_trade_no' => $data['outTradeNo'], //商户订单号，需要保证不重复
                'scene' => 'bar_code',                 //条码支付固定传入bar_code
                'auth_code' => $data['authCode'],      //用户付款码，25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准
                'total_amount' => $data['totalFee'],   //商户订单号，需要保证不重复;单位 元
                'subject' => $data['orderName'],       //订单标题
                'store_id' => 'DEDEMAO_001',          //商户门店编号
                'timeout_express' => '2m',            //交易超时时间
            ]
        ];

        $result = $this->curlPost($this->merge($request));
        return json_decode($result, true);
    }

    /*
    * 查询订单
    * 该接口提供所有支付宝支付订单的查询，商户可以通过该接口主动查询订单状态，完成下一步的业务逻辑。 需要调用查询接口的情况： 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知； 调用支付接口后，返回系统错误或未知交易状态情况； 调用alipay.trade.pay，返回INPROCESS的状态； 调用alipay.trade.cancel之前，需确认支付状态；
    */
    public function query($data = array())
    {
        $request = [
            'type' => 'alipay.trade.query',
            'reqs' => [
                'out_trade_no' => @$data['outTradeNo'], //商户订单号，需要保证不重复
                'trade_no' => @$data['tradeNo'], //商户订单号，需要保证不重复
            ]
        ];

        $result = $this->curlPost($this->merge($request));
        return $result;
    }

    /*
    * 交易撤销
    * 支付交易返回失败或支付系统超时，调用该接口撤销交易。如果此订单用户支付失败，支付宝系统会将此订单关闭；如果用户支付成功，支付宝系统会将此订单资金退还给用户。 注意：只有发生支付系统超时或者支付结果未知时可调用撤销，其他正常支付的单如需实现相同功能请调用申请退款API。提交支付交易后调用【查询订单API】，没有明确的支付结果再调用【撤销订单API】。
    */
    public function cancel($data = array())
    {
        $request = [
            'type' => 'alipay.trade.cancel',
            'reqs' => [
                'out_trade_no' => @$data['outTradeNo'], //商户订单号，需要保证不重复
                'trade_no' => @$data['tradeNo'], //商户订单号，需要保证不重复
            ]
        ];

        $result = $this->curlPost($this->merge($request));
        return $result;
    }

    /*
    * 交易退款
    * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，支付宝将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。 交易超过约定时间（签约时设置的可退款时间）的订单无法进行退款 支付宝退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。一笔退款失败后重新提交，要采用原来的退款单号。总退款金额不能超过用户实际支付金额
    */
    public function refund($data = array())
    {
        $request = [
            'type' => 'alipay.trade.refund',
            'reqs' => [
                'out_trade_no' => @$data['outTradeNo'],    //商户订单号，需要保证不重复
                'trade_no' => @$data['tradeNo'],           //支付宝订单号，不能和out_trade_no同时为空
                'refund_amount' => $data['refundAmount'], //需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
                'refund_reason' => $data['refundReason'], //退款的原因说明
            ]
        ];

        $result = $this->curlPost($this->merge($request));
        return $result;
    }

    /*
    * 下载账单
    * 为方便商户快速查账，支持商户通过本接口获取商户离线账单下载地址
    */
    public function billDownload($data = array())
    {
        $request = [
            'type' => 'alipay.data.dataservice.bill.downloadurl.query',
            'reqs' => [
                'bill_type' => $data['billType'],    //账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
                'bill_date' => $data['billDate'],    //账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
            ]
        ];

        $result = $this->curlPost($this->merge($request, false));
        return $result;
    }

    //合并请求参数
    public function merge($request, $notify = true)
    {
        $data = [
            //公共参数
            'app_id' => $this->appId,     //
            'method' => $request['type'], //接口名称
            'format' => 'JSON',
            'charset' => $this->charset,
            'sign_type' => $this->sign_type,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'notify_url' => $this->notifyUrl,
            'biz_content' => json_encode($request['reqs']),
        ];

        if (is_null($this->notifyUrl) || $notify = false) {
            unset($data['notify_url']);
        }

        $data['sign'] = $this->generateSign($data, $this->sign_type);
        return $data;
    }

    //验证签名
    public function rsaCheck($params)
    {
        $sign = $params['sign'];
        $signType = $params['sign_type'];
        unset($params['sign_type']);
        unset($params['sign']);
        return $this->verify($this->getSignContent($params), $sign, $signType);
    }

    function verify($data, $sign, $signType = 'RSA')
    {
        $pubKey = $this->alipayPublicKey;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, version_compare(PHP_VERSION, '5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        return $result;
    }

    //生成签名
    public function generateSign($params, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $signType);
    }

    protected function sign($data, $signType = "RSA")
    {
        $priKey = $this->rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION, '5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    public function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    public function curlPost($postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        if (!$this->ssl){
            //https请求 不验证证书和host
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}