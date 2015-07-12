<?php
/**
 * Encrypt.php
 *
 * Part of Allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\Allinpay\Tools;

class Encrypt{

    /**
     * 进行MD5签名
     * @param array $args   要签名的数组
     * @param $key  密钥
     * @return string   签名后的MD5字符串
     */
    public static function MD5_sign(array $args, $key){

        $args['key'] = $key;

        $url_params = urldecode(http_build_query($args));

        return strtoupper(md5($url_params));

    }

}

