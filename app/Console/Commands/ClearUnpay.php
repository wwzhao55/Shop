<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand\Brand,App\Models\Order\Order;

class ClearUnpay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:unpay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear unpay order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // do stuff here
        $brand_list = Brand::where('status',1)->select('id','brandname')->get();
        foreach ($brand_list as $brand) {
            //遍历品牌
            $Order = new Order;
            $Order->setTable($brand->brandname.'_order');
            $Order->where('status',1)->where('order_at','<',time()-60*60)->update(['status'=>5,'close_type'=>1]);
        }
    }
}
