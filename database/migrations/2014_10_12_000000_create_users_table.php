<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //此表只用作用户登录
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account');//用户账号
            $table->string('password');//用户密码
            $table->integer('brand_id');//超级管理员为0;
            $table->integer('shop_id');//超级管理员为0;
            $table->tinyInteger('role');//账号角色 0：超级管理员 1：品牌管理员 2：店铺超级管理员（移除） 3：小店管理员 4：顾客 5：店铺员工
            $table->rememberToken();//用于‘记住密码’
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
        Schema::drop('users');
    }
}
