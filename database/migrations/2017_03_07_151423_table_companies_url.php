<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCompaniesUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            DB::statement("ALTER TABLE companies CHANGE COLUMN url url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN facebook_url facebook_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN linkedin_url linkedin_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN twitter_url twitter_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  crunchbase_url  crunchbase_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  ios_url  ios_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  android_url  android_url text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  url_to_redirect  url_to_redirect text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN  compensation  compensation text NULL");
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
