<?php
/**
 * Created by PhpStorm.
 * User: lilichun
 * Date: 2015/7/10 0010
 * Time: 10:55
 */

namespace App\Libraries\Allinpay;

use InvalidArgumentException;

class Pay extends BaseService{

    const PAY_TYPE_PERSONAL = 0;    //个人网银支付
    const PAY_TYPE_ENTERPRISE = 4;  //企业网银支付
    const PAY_TYPE_WAP = 10;    //WAP支付
    const PAY_TYPE_CREDIT = 11; //信用卡支付
    const PAY_TYPE_QUICK = 12;  //快捷支付
    const PAY_TYPE_AUTH = 21;   //认证支付
    const PAY_TYPE_WILDCARD = 23;   //外卡支付

    const LANGUAGE_ZH_S = 1;  //简体中文
    const LANGUAGE_ZH_T = 2;    //繁体中文
    const LANGUAGE_EN = 3;  //英文

    const CHAR_UTF8 = 1;
    const CHAR_GBK = 2;
    const CHAR_GB2312 = 3;

    const TRADE_TYPE_GOODS = 'GOODS';   //实体物品交易
    const TRADE_TYPE_SERVICES = 'SERVICES'; //服务类交易

    const CURRENCY_RMB = 0;  //人民币
    const CURRENCY_DOLLARS = 840;    //美元
    const CURRENCY_HK = 344; //港币

    protected function properties(){
        return [
            'inputCharset', 'pickupUrl', 'receiveUrl', 'version', 'language', 'signType', 'merchantId', 'payerName', 'payerEmail', 'payerTelephone', 'payerIDCard', 'pid', 'orderNo', 'orderAmount', 'orderCurrency', 'orderDatetime', 'orderExpireDatetime', 'productName', 'productPrice', 'productNum', 'productId', 'productDesc', 'ext1', 'ext2', 'extTL', 'payType', 'issuerId', 'pan', 'tradeNature', 'signMsg'
        ];
    }

    public function __construct($url, $merchantId, $key){
        parent::__construct($url, $merchantId, $key);
        $this->charSet()->setSignType()->setCurrency();
    }

    final public function setLanguage($language = 1){

        if($language === self::LANGUAGE_ZH_S || $language === self::LANGUAGE_ZH_T || $language === self::LANGUAGE_EN){

            $this->value['language'] = $language;

            return $this;

        }
        throw new InvalidArgumentException('暂不支持此语言!');
    }

    public function setSignType($EncryptType = 0){

        if($EncryptType === self::ENCRYPT_MD5 || $EncryptType === self::ENCRYPT_OTHER){

            $this->value['signType'] = $EncryptType;

            return $this;

        }

        throw new InvalidArgumentException('暂不支持此加密算法!');
    }

    final public function setCurrency($currencyType = 0, $tradeType = null){
        if($currencyType === self::CURRENCY_DOLLARS || $currencyType === self::CURRENCY_HK || $currencyType === self::CURRENCY_RMB){

            if($currencyType !== self::CURRENCY_RMB && is_null($tradeType)){

                throw new InvalidArgumentException('设置了非人民币的货币，就必须设置贸易类型!');

            }

            $this->value['orderCurrency'] = $currencyType;

            return $this;

        }

        throw new InvalidArgumentException('暂不支持此货币!');

    }

    public function charSet($charSet = 1){

        if(in_array($charSet, [self::CHAR_UTF8, self::CHAR_GBK, self::CHAR_GB2312])){

            $this->value['inputCharset'] = $charSet;

            return $this;

        }

        throw new InvalidArgumentException('暂不支持此字符集!');
    }

    final public function setUrl($pikUpUrl = '', $receiveUrl = ''){
        if($pikUpUrl){

            $this->value['pickupUrl'] = $pikUpUrl;

        }
        if($receiveUrl){

            $this->value['receiveUrl'] = $receiveUrl;

        }

        return $this;
    }

    final public function parameter($orderNo, $orderAmount, $payType = 0){

        if(empty($orderNo) || empty($orderAmount)){

            throw new InvalidArgumentException('缺少重要参数!');

        }

        if(strlen($orderNo) > 50){

            throw new InvalidArgumentException('订单号长度不能超过50!');

        }

        if(!is_int($orderAmount)){

            throw new InvalidArgumentException('订单金额单位不正确,单位为分!');

        }

        $this->value['orderDatetime'] = date('YmdHis', time());
        $this->value['orderNo'] = $orderNo;
        $this->value['orderAmount'] = $orderAmount;

        $payTypeList = [
            self::PAY_TYPE_PERSONAL,
            self::PAY_TYPE_ENTERPRISE,
            self::PAY_TYPE_WAP,
            self::PAY_TYPE_CREDIT,
            self::PAY_TYPE_QUICK,
            self::PAY_TYPE_AUTH,
            self::PAY_TYPE_WILDCARD,

        ];

        if(!in_array($payType ,$payTypeList)){

            throw new InvalidArgumentException('暂不支持此支付方式!');

        }

        $this->value['payType'] = $payType;

        $data = [];

        foreach($this->properties as $args){

            if(isset($this->value[$args]) && $args != 'signMsg'){

                $data[$args] = $this->value[$args];

            }

        }

        $data['key'] = $this->config['md5key'];

        $data['signMsg'] = strtoupper(md5(urldecode(http_build_query($data))));

        unset($data['key']);

        $this->postData = $data;

        return $this;

    }


}