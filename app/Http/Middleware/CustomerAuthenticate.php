<?php

namespace App\Http\Middleware;

use Closure,Session;
use Illuminate\Contracts\Auth\Guard;

class CustomerAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
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
     *顾客角色登录才能进行下一步操作！！
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else{
                return redirect()->guest('/shop/login');
            }
        }else{
            if($this->auth->user()->role != 4){
                return redirect()->guest('/error');
            }else{
                return $next($request);
            }
        }


    }
}
