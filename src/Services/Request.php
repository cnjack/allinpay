<?php
/**
 * Request.php
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
use InvalidArgumentException;
use Fakeronline\Allinpay\Utils\Arr;
use Fakeronline\Allinpay\Utils\Form;

abstract class Request{

    use ServiceTrait;

    const VERSION_10 = 'v1.0';
    const VERSION_13 = 'v1.3';
    const VERSION_15 = 'v1.5';
    const VERSION_20 = 'v2.0';

    const ENCRYPT_MD5 = 0;  //订单上送和交易结果通知都使用MD5签名
//    const ENCRYPT_OTHER = 1;    //商户用MD5算法验签上送订单，通联交易结果通知使用证书签名 TODO：暂时只支持MD5加密方式

    protected $config;  //用户存储配置
    protected $properties = [];  //存储参数KEY
    protected $value;   //存储参数键值
    protected $postData;

    public function __construct($url, $merchantId, $key){

        if(empty($url) || empty($merchantId) || empty($key)){

            throw new InvalidArgumentException('请求URL，商户号，MD5KEY为必要参数!');

        }

        $this->config = [
            'url' => $url,
            'merchantId' => $merchantId,
            'md5key' => $key
        ];

        $properties = $this->properties();
        $this->properties = array_merge($this->properties, (array)$properties );    //设置参数KEY

        $this->value['merchantId'] = $this->config['merchantId'];   //设置商户号

        $this->value['version'] = self::VERSION_10; //设置默认版本号为1.0版本


    }

    public function setVersion ($version = 'v1.0'){

        $this->value['version'] = $version;
        return $this;
    }

    public function setSignType($EncryptType = 0){

        if($EncryptType === self::ENCRYPT_MD5){

            $this->value['signType'] = $EncryptType;

            return $this;

        }

        throw new InvalidArgumentException('暂不支持此签名类型!');
    }

    abstract protected function properties();

    protected function verify(){

        return true;
    }

    public function request(){

        if(!$this->verify()){

            throw new InvalidArgumentException('非法操作!');

        }

        $form = new Form($this->config['url'], '正在进入系统');
        $form->setData($this->postData)->submit();
    }


    public function __set($key, $value){

        if(in_array($key, $this->properties)){

            $this->value[$key] = $value;

        }

    }

    public function __get($key){

        return Arr::get($this->value, $key, '');

    }

    public function __call($name, $args){

        if(in_array($name, $this->properties)){

            $this->value[$name] = $args;

        }

        return $this;

    }

}
 

