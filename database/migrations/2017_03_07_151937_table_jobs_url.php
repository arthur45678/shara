<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableJobsUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            DB::statement("ALTER TABLE companies CHANGE COLUMN  url_to_redirect  url_to_redirect text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  city_latitude  city_latitude text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  city_longtitude  city_longtitude text NULL");
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
    }
}
