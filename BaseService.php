<?php
/**
 * Created by PhpStorm.
 * User: lilichun
 * Date: 2015/7/10 0010
 * Time: 10:39
 */

namespace App\Libraries\Allinpay;

use App\Libraries\Allinpay\Utils\Arr;
use App\Libraries\Allinpay\Utils\Curl;
use InvalidArgumentException;

abstract class BaseService{

    const VERSION_10 = 'v1.0';
    const VERSION_20 = 'v2.0';

    const ENCRYPT_MD5 = 0;  //�������ͺͽ��׽��֪ͨ��ʹ��MD5ǩ��
    const ENCRYPT_OTHER = 1;    //�̻���MD5�㷨��ǩ���Ͷ�����ͨ�����׽��֪ͨʹ��֤��ǩ��

    protected $config;
    protected $properties = [];
    protected $value = [];
    protected $postData = null;


    public function __construct($url, $merchantId, $key){

        if(empty($url) || empty($merchantId) || empty($key)){

            throw new InvalidArgumentException('ȱ�ٱ�Ҫ����!');

        }

        $this->config = [
            'url' => $url,
            'merchantId' => $merchantId,
            'md5key' => $key
        ];

        $properties = (array)($this->properties()); //��ȡ���в���

        $this->properties = array_merge($this->properties, $properties);   //�洢���в���

        $this->value['merchantId'] = $this->config['merchantId'];  //�����̻���

        $this->setVersion();    //��������
    }

    final public function setVersion ($version = 'v1.0'){

        if(in_array($version, [self::VERSION_10, self::VERSION_20])){

            $this->value['version'] = $version;
            return $this;

        }
        throw new InvalidArgumentException('�ݲ�֧�ִ˰汾!');
    }

    protected function verify(){

        return true;
    }

    /**
     * �������в�������
     * @return array    ���в�������
     */
    abstract protected function properties();

    public function request(){

        if(!$this->verify()){

            throw new InvalidArgumentException('�Ƿ�����!');

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
            <title>���ڽ���֧��ϵͳ������</title>
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