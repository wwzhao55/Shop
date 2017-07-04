<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('brand_theme', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->integer('theme_id');
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
        Schema::drop('brand_theme');
    }
}
