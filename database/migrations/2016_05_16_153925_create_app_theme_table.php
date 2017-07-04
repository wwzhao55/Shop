<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('app_theme', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');//主题名称
            $table->string('background_color');
            $table->string('url');
            $table->string('font');
            $table->tinyInteger('price');
            $table->text('description');
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
        Schema::drop('app_theme');
    }
}
