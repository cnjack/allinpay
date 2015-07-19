<?php
/**
 * Pay.php
 *
 * Part of Allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\Allinpay\Responses;
use Fakeronline\Allinpay\Exception;
use Fakeronline\Allinpay\Services\Response;
use Fakeronline\Allinpay\Utils\Arr;

class Pay extends Response{

    protected function properties(){
        return [
            'merchantId', 'version', 'language', 'signType', 'payType', 'issuerId', 'paymentOrderId', 'orderNo', 'orderDatetime', 'orderAmount', 'payDatetime', 'payAmount', 'ext1', 'ext2', 'payResult', 'errorCode', 'returnDatetime', 'signMsg'
        ];
    }

    public function getOrderNo(){
        return Arr::get($this->value, 'orderNo');
    }

}

