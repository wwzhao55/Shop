<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//参数全局过滤
Route::pattern('id', '[1-9][0-9]?');


Route::group(['namespace'=>'Home'],function(){
	foreach(['auth','home'] as $controller){
        Route::controller($controller, ucfirst($controller).'Controller');
    }
    /*登陆后的入口,根据不同角色分配到各自主页*/
    Route::get('/','HomeController@getIndex');
    /*注册 登录 注销 等*/
    Route::get('/login','AuthController@getLogin');
    Route::post('/login','AuthController@postLogin');
    Route::get('/logout','AuthController@getLogout');
});


/*----------超级管理员------------*/
Route::group(['prefix'=>'Admin','namespace'=>'Admin'], function () {
    #----------数据中心首页路由
    Route::get('/','DatacenterController@getIndex');
    #---------app管理
    Route::group(['prefix'=>'App','namespace'=>'App'],function(){
        Route::controller('material', 'MaterialController');
        Route::controller('theme', 'ThemeController');
        Route::controller('startlogo', 'StartlogoController');
        Route::controller('advertisement', 'AdvertisementController');
    });

	#-------------分类管理
    Route::controller('category', 'CategoryController');
    #----------品牌管理
    Route::controller('brandmanage', 'BrandmanageController');
    #---------小店管理
    Route::controller('shopmanage','ShopmanageController');
    #-----------数据中心
    Route::controller('datacenter', 'DatacenterController');

    
});

/*----------品牌管理员------------*/
Route::group(['prefix'=>'Brand','namespace'=>'Brand'], function () {
    #----------数据中心首页路由
	Route::get('/','DatacenterController@getIndex');
    #---------公众号管理
    Route::controller('publicmanage', 'PublicmanageController');
    #-----------数据中心
    Route::controller('datacenter', 'DatacenterController');
    //Route::controller('autoreply', 'AutoreplyController');
    #---------小店管理
    Route::controller('shopmanage','ShopmanageController');
    #---------app管理
    Route::controller('app','AppController');
    #-----------商品管理
    Route::controller('commodity', 'CommodityController');
    #-------------分类管理
    Route::controller('group', 'GroupController');
    #-----------优惠券管理
    Route::controller('coupon','CouponController');
    #------------小店员工管理
    Route::controller('staffmanage', 'StaffmanageController');
    #-----------轮播图管理
    Route::controller('shuffling', 'ShufflingController');
});

/*----------店铺超级管理员------------*/
Route::group(['prefix'=>'Shopadmin','namespace'=>'Shopadmin'], function () {
    #----------数据中心首页路由
    Route::get('/','DatacenterController@getIndex');
    #-----------数据中心
    Route::controller('datacenter', 'DatacenterController');
    #------------店员管理
    Route::controller('staffmanage', 'StaffmanageController');


});

/*----------店铺管理员------------*/
Route::group(['prefix'=>'Shopstaff','namespace'=>'Shopstaff'], function () {
    #-----------商品管理首页路由
    Route::get('/','CommodityController@getIndex');
    #-----------商品管理
    Route::controller('commodity', 'CommodityController');

    Route::controller('apporder', 'ApporderController');//add by lilei
    Route::controller('weborder', 'WeborderController');//add by lilei


});


# ------------------ Api ------------------------
Route::group(array('prefix'=>'Api','namespace'=>'Api'), function(){
    foreach(['weixin','auth'] as $controller){
        #------------微信小店接口
        Route::controller($controller, ucfirst($controller).'Controller');
    }
});


# ------------------ 微信小店 ------------------------

Route::group(['prefix'=>'shop','namespace'=>'Shop'],function(){
    foreach(['vip','address','shopcart','order','coupon','front','auth'] as $controller){
        Route::controller($controller,ucfirst($controller).'Controller');
    }
    Route::get('gateway','AuthController@getGateway');
    Route::get('login','AuthController@getLogin');
    Route::get('register','AuthController@getRegister');
    Route::get('front/detail',['uses'=>'FrontController@getDetail']);
    Route::post('login','AuthController@postLogin');
    Route::post('register','AuthController@postRegister');
    Route::post('message','AuthController@postMessage');
    Route::get('error',['uses'=>'AuthController@getWxerror']);
});

# ------------------ app ------------------------
Route::controllers([
    'clerk' => 'app\ClerkController',
    'customer' => 'app\CustomerController',
    'app'=>'app\PublicController'
]);


/*-------------错误页面-------------------*/
Route::get('/error',function(){
    return View::make('errors.error');
});

#--------------微信授权------------------------
Route::post('/Auth/message',['uses'=>'Api\AuthController@postMessage']);
