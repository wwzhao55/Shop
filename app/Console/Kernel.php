<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        'App\Console\Commands\ClearUnpay',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
        //删除未付款订单 1小时
        $schedule->command('clear:unpay')->everyTenMinutes();
        
        //清理数据库 无效商品
       // $schedule->call('App\Http\Controllers\Brand\CommodityController@postCleardata')->dailyAt('02:00');
    }
}
