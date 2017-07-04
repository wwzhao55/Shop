<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */

     private $openRoutes =['clerk/goodList','app/customerlogin','app/*','clerk/*','app/test','app/register','customer/*','customer/dishesList','Api/weixin/*','Brand/*','Admin/*','shop/order/notify','shop/order/notify/*','shop/order/cancelunpay','Auth/message'];//自定义不需要csrf验证的路由



	public function handle($request, Closure $next)
    {
        foreach($this->openRoutes as $route){
            if($request->is($route)){
                return $next($request);
            }
        }
		return parent::handle($request, $next);
    }
}
