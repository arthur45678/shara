<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->enum('type', ['generic', 'subsidiary']);
            $table->string('name')->default('');
            $table->integer('parent_id')->references('id')->on('copmanies');
            $table->string('url')->default('');
            $table->string('description')->default('');
            $table->string('short_description')->default('');
            $table->string('logo')->default('');
            $table->string('facebook_url')->default('');
            $table->string('linkedin_url')->default('');
            $table->string('twitter_url')->default('');
            $table->string('crunchbase_url')->default('');
            $table->string('ios_url')->default('');
            $table->string('android_url')->default('');
            $table->integer('country_id')->default(0);
            $table->integer('city_id')->default(0);
            $table->integer('sector_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('looking_for')->default('');
            $table->string('requirement')->default('');
            $table->string('compensation')->default('');
            $table->string('why_us')->default('');
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
        Schema::drop('companies');
    }
}
