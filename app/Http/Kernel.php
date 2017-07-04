<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'customer' => \App\Http\Middleware\CustomerAuthenticate::class,
        'shopstaff' => \App\Http\Middleware\ShopstaffAuthenticate::class,
        'shopadmin' => \App\Http\Middleware\ShopadminAuthenticate::class,
        'brand' => \App\Http\Middleware\BrandAuthenticate::class,
        'admin' => \App\Http\Middleware\AdminAuthenticate::class,
        'pageview' => \App\Http\Middleware\PageviewRecord::class,
        'wxentry' => \App\Http\Middleware\Wxentry::class,
        'shoprest' => \App\Http\Middleware\Shoprest::class,

    ];
}
