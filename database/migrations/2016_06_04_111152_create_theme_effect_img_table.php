<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeEffectImgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('theme_effect', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_id');
            $table->string('img_src');
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
        Schema::drop('theme_effect');
    }
}
