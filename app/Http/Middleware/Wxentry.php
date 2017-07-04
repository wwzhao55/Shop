<?php

namespace App\Http\Middleware;

use Closure,Session;
use Illuminate\Contracts\Auth\Guard,App\Models\Brand\Brand;

class Wxentry
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->is_weixin()){
            return $next($request);
        }else{
            return redirect('/shop/error');
        }
    }

    private function is_weixin(){ 
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
         return true;
        }
         return false; 
    }  
        
}
