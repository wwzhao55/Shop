<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandstatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //数据统计（最小单位为品牌，每个品牌每天会产生一条数据）
        Schema::create('brandstatistic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');//数据统计的店铺id
            $table->integer('shop_count');//新增店铺数
            $table->integer('customer_count');//品牌某日顾客数
            $table->integer('fans_count');//品牌某日粉丝数
            $table->integer('submit_order_count');//品牌某日订单数
            $table->integer('waitdelivery_order_count');//品牌某日订单数
            $table->integer('finished_order_count');//品牌某日订单数
            $table->integer('failed_order_count');//品牌某日订单数
            $table->integer('visiter_count');//品牌某日访问量
            $table->float('total',10,2);//新增交易额
            $table->float('all_total',10,2);//总交易额
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('brandstatistic');
    }
}
