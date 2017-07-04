<?php

namespace App\Http\Middleware;

use Closure,Session;
use Illuminate\Contracts\Auth\Guard,App\Models\Brand\Brand,App\Models\Shop\Shopinfo;

class Shoprest
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
        if ($request->isMethod('post')) {
            return $next($request);
        }
        if ($request->is('shop/order/*') || $request->is('shop/front/rest') || $request->is('shop/error') || $request->is('shop/vip') || $request->is('shop/vip/index') || $request->is('shop/front/checkoauth/*') || $request->is('shop/gateway') || $request->is('shop/address/checkoauth/*') || $request->is('Api/Weixin/*')) {
            return $next($request);
            //return $request->path();
        }
        if($request->has('shop_id')){
            $shop = Shopinfo::find($request->shop_id);
            Session::put('shop_id',$request->shop_id);
        }else{
            if(Session::has('shop_id')){
                $shop = Shopinfo::find(Session::get('shop_id'));
            }else{
                $shop = Shopinfo::find(1);
                Session::put('shop_id',1);
            }
            
        }
        if(strpos($shop->open_at,'上午')!==false){
            $open_at = str_replace('上午', 'am', $shop->open_at);
            $open_time = strtotime(date('Y-m-d ').$open_at);                
        }else if(strpos($shop->open_at,'下午')!==false){
            $open_at = str_replace('下午', 'pm', $shop->open_at);
            $open_time = strtotime(date('Y-m-d ').$open_at);
        }else{
            //未设置营业时间
            return $next($request);
        }
        if(strpos($shop->close_at,'上午')!==false){
            $close_at = str_replace('下午', 'pm', $shop->close_at);
            $close_time = strtotime(date('Y-m-d ').$close_at);
        }else if(strpos($shop->close_at,'下午')!==false){
            $close_at = str_replace('下午', 'pm', $shop->close_at);
            $close_time = strtotime(date('Y-m-d ').$close_at);
        }else{
             return $next($request);
        }
        //var_dump($open_time,time(),$close_time);
        if($open_time>time() || $close_time<time()){
            
            Session::put('brand_id',$shop->brand_id);
            return redirect('/shop/front/rest');
        }else{
            return $next($request);
        }
    }     
}
