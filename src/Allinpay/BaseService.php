<?php
/**
 * BaseService.php
 *
 * Part of Allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fackeronline
 */


namespace Allinpay;

use Allinpay\Utils\Arr;
use Allinpay\Utils\Curl;
use InvalidArgumentException;

abstract class BaseService{

    const VERSION_10 = 'v1.0';
    const VERSION_20 = 'v2.0';

    const ENCRYPT_MD5 = 0;  //订单上送和交易结果通知都使用MD5签名
    const ENCRYPT_OTHER = 1;    //商户用MD5算法验签上送订单，通联交易结果通知使用证书签名

    protected $config;
    protected $properties = [];
    protected $value = [];
    protected $postData = null;


    public function __construct($url, $merchantId, $key){

        if(empty($url) || empty($merchantId) || empty($key)){

            throw new InvalidArgumentException('缺少必要参数!');

        }

        $this->config = [
            'url' => $url,
            'merchantId' => $merchantId,
            'md5key' => $key
        ];

        $properties = (array)($this->properties()); //获取须有参数

        $this->properties = array_merge($this->properties, $properties);   //存储须有参数

        $this->value['merchantId'] = $this->config['merchantId'];  //设置商户号

        $this->setVersion();    //常规设置
    }

    final public function setVersion ($version = 'v1.0'){

        if(in_array($version, [self::VERSION_10, self::VERSION_20])){

            $this->value['version'] = $version;
            return $this;

        }
        throw new InvalidArgumentException('暂不支持此版本!');
    }

    protected function verify(){

        return true;
    }

    /**
     * 返回须有参数数组
     * @return array    须有参数数组
     */
    abstract protected function properties();

    public function request(){

        if(!$this->verify()){

            throw new InvalidArgumentException('非法操作!');

        }

        $html_str = '';
        foreach($this->postData as $key => $args ){
            $html_str.='<input type="hidden" name="'.$key.'" value="'.$args.'" />';
        }
        $code='
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>正在进入支付系统。。。</title>
            </head>
            <body>
            <form id="data_form" action="'.$this->config['url'].'" method="post">'.$html_str.'</form>
            <script type="text/javascript">document.getElementById("data_form").submit();</script>
            </body>
            </html>
		';
        return $code;

//        $curl = new Curl($this->config['url']);
////        dump($this->postData);
////        die;
//        return $curl->setData($this->postData)->get();
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

    }

}