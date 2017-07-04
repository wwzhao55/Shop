<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShufflingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shuffling', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->integer('shop_id');//店铺id
            $table->string('name');
            $table->string('img_src');//图片地址
            $table->string('http_src');//点击图片跳转地址
            $table->boolean('status');//图片状态
            $table->tinyinteger('order');//图片排序
            $table->integer('created_at');
            $table->integer('updated_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shuffling');
    }
}
