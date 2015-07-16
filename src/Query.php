<?php
/**
 * Query.php
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
use Fakeronline\Allinpay\Tools\Encrypt;

class Query extends Request{

    protected function properties(){
        return [
            'merchantId', 'version', 'signType', 'orderNo', 'orderDatetime', 'queryDatetime', 'signMsg'
        ];
    }

    public function __construct($url, $merchantId, $key){

        parent::__construct($url, $merchantId, $key);
        $this->setSignType()->setVersion(self::VERSION_15);   //设置加密类型
    }

    final public function parameter($orderNo, $orderDatetime, $queryDatetime){

        if( empty($orderNo) || empty($orderDatetime) || empty($queryDatetime) ){
            throw new Exception('订单编号、订单时间和订单查询时间为必要参数!');
        }

        $this->value['orderNo'] = $orderNo;
        $this->value['orderDatetime'] = $orderDatetime;
        $this->value['queryDatetime'] = $queryDatetime;

        $this->postData = $this->sort($this->properties, $this->value);

        $this->postData['signMsg'] = Encrypt::MD5_sign($this->postData, $this->config['md5key']);

        return $this;
    }

}
 

