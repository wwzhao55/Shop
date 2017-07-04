<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopstaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //店铺账号表
        Schema::create('shopstaff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');//uid 对应users表中的id（认证时使用）
            $table->integer('shop_id');//店铺id
            $table->string('name');//员工姓名
            $table->string('phone');
            $table->string('email');
            $table->tinyInteger('status');//账号状态
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
        Schema::drop('shopstaff');
    }
}
