<?php
/**
 * Response.php
 *
 * Part of Allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\Allinpay\Services;
use Fakeronline\Allinpay\Tools\Encrypt;
use Exception;

abstract class Response{

    use ServiceTrait;

    const ENCRYPT_MD5 = 0;  //订单上送和交易结果通知都使用MD5签名
//    const ENCRYPT_OTHER = 1;    //商户用MD5算法验签上送订单，通联交易结果通知使用证书签名 TODO：暂时只支持MD5加密方式

    protected $properties = [];
    protected $key;
    protected $value;
    public $errorMsg;

    public function __construct($key){

        if(empty($key)){
            throw new Exception('未传入解密KEY!');
        }

        $this->key = $key;
        $properties = $this->properties();
        $this->properties = array_merge($this->properties, (array)$properties);

    }

    abstract protected function properties();

    final protected function verify(){

        if(empty($this->value)){
            return false;
        }

        $this->value = $this->sort($this->properties, $this->value);

        $originalSign = $this->value['signMsg'];
        unset($this->value['signMsg']);

        $sign = '';
        //TODO：因为目前仅支持MD5加密方式
        if($this->value['signType'] == self::ENCRYPT_MD5){
            $sign = Encrypt::MD5_sign($this->value, $this->key);
        }

        return $sign === $originalSign;
    }

    abstract public function chkVerify($args);

}
