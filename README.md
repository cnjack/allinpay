## Allinpay(通联)支付接口

在网上逛了一大圈，发觉没有通联支付接口类库，看到的竟是些官方的demo。那种demo套在自家程序里简直就是放了一堆垃圾，大量的IF ELSE，有些方法实际是可以用PHP内置的函数去实现的，不够抽象，冗余比较多，不适应需求。
* 本类库有以下优点
	* 高度抽象和适配
	* 隐藏开发者不需要知道的细节
	* 不需要再排序，也不需要手动签名
	* 一键验证通联消息
	* 符合 [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) 标准，你可以各种方便的与你的框架集成

### 安装使用
环境要求：PHP >= 5.3.0

##### 基本使用（以支付/充值为例）
```php
<?php 
$pay = new \Allinpay\Pay(URL, MERCHANTID, KEY); //创建支付实例
$pay->setUrl(BASE_URL . 'sync.php' , BASE_URL . 'asyn.php');    //设置同步URL和异步URL(单选或多选)
$pay->setLanguage();    //设置语言，默认为中文    可选项
$pay->parameter(['orderNo' =>date('Ymdhis'), 'orderAmount' => 200 ])->request();	
```

就是这么简单。默认是支持连贯操作的，你还可以找回TP和Laravel的感觉哦，比如:
```php
$pay = new \Allinpay\Pay(URL, MERCHANTID, KEY); //创建支付实例
$pay->setUrl(BASE_URL . 'sync.php' , BASE_URL . 'asyn.php')->->parameter(['orderNo' =>date('Ymdhis'), 'orderAmount' => 200 ])->request();
```
还可以这样
```php
$pay = new \Allinpay\Pay(URL, MERCHANTID, KEY);
$pay->Version = $pay::VERSION_10;	//只要你记得官方文档的KEY,就可以这样设置值
```
或者这样
```php
$pay = new \Allinpay\Pay(URL, MERCHANTID, KEY);
$pay->Version($pay::VERSION_10);
```


##### 响应请求，验证数据
```php
$pay = new \Allinpay\Responses\Pay(KEY);
$result = $pay->chkVerify($_REQUEST);

if($result){
    if($pay->errorMsg){
        echo $pay->errorMsg;
    }else{
        echo '支付成功!';
    }
}else{
    echo '不是通联数据，我们不鸟它!';
}
```

响应请求是最方便的，只需要传入加密的KEY，以及发过来的消息，就可以知道是不是通联传过来的数据，并且自动验证，验证结果如果是的话就返回 <code> true</code>，否则就返回<code>false</code>。返回 <code>true</code>后我们可以通过 <code>errorMsg</code>得到订单结果。

-----------------------------------------------------------------------------------------------------
更多请参考文档。

怎样，是不是很简单？赶紧下载安装试试看吧~~~

## License
如有修改本类库的内容为自己使用，请标识类库的出处
[Fakeronline](https://github.com/Fakeronline)<<https://github.com/Fakeronline>>
联系邮箱：1077341744@qq.com
MIT