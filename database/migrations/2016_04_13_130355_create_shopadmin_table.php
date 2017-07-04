<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopadminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商户总店账号表
        Schema::create('shopadmin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');//uid 对应users表中的id（认证时使用）
            $table->integer('brand_id');//品牌id
            $table->string('phone');
            $table->string('email');
            $table->boolean('status');//账号状态
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
        Schema::drop('shopadmin');
    }
}
