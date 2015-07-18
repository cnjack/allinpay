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

        //��д�ⷽ����ԭ�������˿�ӿ�ֻ֧�����ּ�������

        if($EncryptType === self::ENCRYPT_MD5){

            $this->value['signType'] = $EncryptType;

            return $this;

        }

        throw new Exception('�ݲ�֧�ִ�ǩ������!');
    }

    final public function parameter($orderNo, $refundAmount, $orderDatetime){

        if(empty($orderNo) || empty($refundAmount) || empty($orderDatetime)){

            throw new Exception('������š��˿���Ͷ����ύʱ���Ǳش�����');
        }

        if(strlen($orderNo) > 50){

            throw new Exception('������Ŵ���!');
        }

        if(round($refundAmount, 2) != $refundAmount){

            throw new Exception('����ȷ����֧�ֵ���!');
        }

        if(strlen($orderDatetime) !=14){

            throw new Exception('�����ύʱ���ʽ����ΪYmdHis!');
        }

        $this->value['orderNo'] = $orderNo;
        $this->value['refundAmount'] = $refundAmount;
        $this->value['orderDatetime'] = $orderDatetime;

        $this->postData = $this->sort($this->properties, $this->value);

        $this->postData['signMsg'] = Encrypt::MD5_sign($this->postData, $this->config['md5key']);

        return $this;

    }

}
 

