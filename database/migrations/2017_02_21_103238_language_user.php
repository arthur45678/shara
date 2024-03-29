<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanguageUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->timestamps();
            $table->foreign('language_id')
                 ->references('id')
                 ->on('languages')
                 ->onDelete('cascade');
            $table->foreign('user_id')
                 ->references('id')
                 ->on('users')
                 ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('language_user');
    }
}
