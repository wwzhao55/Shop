<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('app_advertisement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_src1');//广告图
            $table->string('image_src2');//广告图
            $table->string('image_src3');//广告图
            $table->string('image_src4');//广告图
            $table->tinyInteger('status');//状态
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
        Schema::drop('app_advertisement');
    }
}
