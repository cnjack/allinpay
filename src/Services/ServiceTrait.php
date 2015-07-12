<?php
/**
 * ServiceTrait.php
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

use Fakeronline\Allinpay\Utils\Arr;

trait ServiceTrait{

    final protected function sort(array $contrastKey, array $data){

        $result = [];

        foreach($contrastKey as $key){

            $result[$key] = Arr::get($data, $key, '');

        }

        $result = array_filter($result, function($value){

            return $value !== '';

        }, ARRAY_FILTER_USE_BOTH);

        return $result;

    }

}
