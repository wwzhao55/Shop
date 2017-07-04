<?php

namespace App\Http\Middleware;

use Closure,Auth;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Statistics\Pageinfo,App\Models\Statistics\Pageview,App\Models\Brand\Brand,App\Models\User,App\Models\Customer\Customer;
class PageviewRecord
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
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get')) {
            //记录浏览记录
            $page_url = $request->path();
            $brand_name = $request->session()->get('brand_name');
            $shop_id = $request->session()->get('shop_id');
            if(!$brand_name){
                //session中没有brand_name
                $brand_id = $request->input('brand_id');
                $brand_name = Brand::find($brand_id)->brandname;
            }
            if(!$shop_id){
                $shop_id = $request->input('shop_id');
            }
            if($shop_id && $brand_name){
                if(Auth::check()){
                    $user_id = Auth::user()->id;
                    $customer = new Customer;
                    $customer->setTable($brand_name.'_customers');
                    $customer_id = $customer->where('uid',$user_id)->first()->id;
                }else{
                    $customer = new Customer;
                    $customer->setTable($brand_name.'_customers');
                    if($request->session()->has('openid')){
                        $customer_id = $customer->where('openid',$request->session()->get('openid'))->first()->id;
                    }else{
                        $customer_id = 0;
                    }   
                    //
                }
            }        
            if(Pageinfo::where('page_url',$page_url)->count()){
                if( $shop_id && $brand_name){
                    $page_id = Pageinfo::where('page_url',$page_url)->first()->id;
                    $pageview = new Pageview;
                    $pageview->setTable($brand_name.'_pageview');
                    $pageview->page_id = $page_id;
                    $pageview->shopid = $shop_id;
                    $pageview->customer_id = $customer_id;
                    $pageview->save();
                }  
            }
        }
        return $next($request);
    }
}
