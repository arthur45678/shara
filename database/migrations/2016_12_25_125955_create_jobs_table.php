<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['generic', 'specific']);
            $table->string('name');
            $table->string('about_company')->default('');
            $table->string('why_us');
            $table->string('benefits')->default('');
            $table->string('requirement'); 
            $table->string('schedule');           
            $table->integer('country_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('sector_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->enum('activation', ['activated', 'deactivated']);
            $table->enum('job_applying', ['redirect', 'form']);
            $table->string('url_to_redirect')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
