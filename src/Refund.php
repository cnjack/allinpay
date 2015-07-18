<?php
/**
 * Refund.php
 *
 * Part of allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\Allinpay;

use Fakeronline\Allinpay\Services\Request;
use Exception;

class Refund extends Request{

    protected function properties(){
        return [
            'version', 'signType', 'merchantId', 'orderNo', 'refundAmount', 'orderDatetime', 'signMsg'
        ];
    }

    public function setSignType($EncryptType = 0){

        //重写这方法的原因在于退款接口只支持这种加密类型

        if($EncryptType === self::ENCRYPT_MD5){

            $this->value['signType'] = $EncryptType;

            return $this;

        }

        throw new Exception('暂不支持此签名类型!');
    }

    final public function parameter($orderNo, $refundAmount, $orderDatetime){

        if(empty($orderNo) || empty($refundAmount) || empty($orderDatetime)){

            throw new Exception('订单编号、退款金额和订单提交时间是必传参数');
        }

        if(strlen($orderNo) > 50){

            throw new Exception('订单编号错误!');
        }

        if(round($refundAmount, 2) != $refundAmount){

            throw new Exception('金额不正确，仅支持到分!');
        }

        if(strlen($orderDatetime) !=14){

            throw new Exception('订单提交时间格式必须为YmdHis!');
        }

        $this->value['orderNo'] = $orderNo;
        $this->value['refundAmount'] = $refundAmount;
        $this->value['orderDatetime'] = $orderDatetime;

        $this->postData = $this->sort($this->properties, $this->value);

        $this->postData['signMsg'] = Encrypt::MD5_sign($this->postData, $this->config['md5key']);

        return $this;

    }

}
 

