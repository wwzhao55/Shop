<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenWeixinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_weixin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');//0第三方  1已授权公众号
            $table->string('appid',255);
            $table->string('appsecret',255);
            $table->string('token',255);
            $table->string('encodingAesKey',255);
            $table->string('component_verify_ticket',255);
            $table->string('component_access_token',255);
            $table->string('pre_auth_code',255);
            $table->string('authorization_code',255);
            $table->string('authorizer_appid',255);
            $table->string('authorizer_access_token',255);
            $table->string('authorizer_refresh_token',255);
            $table->integer('created_at');
            $table->integer('updated_at');
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_weixin');
    }
}
