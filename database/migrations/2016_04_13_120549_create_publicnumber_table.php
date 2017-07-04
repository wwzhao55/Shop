<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicnumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //公众号信息表
        Schema::create('public_number', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');//公众号所属品牌id
            $table->string('name');//公众号名称
            $table->string('appid',64);//公众号appid
            $table->string('appsecret',64);//公众号appsecret
            $table->string('access_token',200);//公众号access_token
            $table->string('token',200);//公众号token
            $table->string('encodingaeskey',64);//公众号encodingaeskey
            $table->smallInteger('service_type_info');//公众号类型
            $table->text('func_info');//接口权限
            $table->string('originalid',100);//公众号原始id
            $table->string('weixin_id',100);//公众号原始id
            $table->string('head_img',255);//
            $table->string('qrcode_url',255);//
            $table->boolean('status');//公众号状态
            $table->text('menu');//公众号菜单
            $table->string('msg_order',100);//模板id
            $table->string('msg_pay',100);//模板id
            $table->string('msg_refund',100);//模板id
            $table->text('subscribe_text');//欢迎消息
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
        Schema::drop('public_number');
    }
}
