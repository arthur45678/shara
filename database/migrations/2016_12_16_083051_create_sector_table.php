<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('activation', ['activated', 'deactivated']);
            $table->timestamps();
        });

        Schema::create('sector_translations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sector_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['sector_id','locale']);
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sector_translations');
        Schema::drop('sectors');

    }
}
