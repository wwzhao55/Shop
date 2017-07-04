<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkunameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuname', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('skuname');
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
        Schema::drop('skuname');
    }
}
