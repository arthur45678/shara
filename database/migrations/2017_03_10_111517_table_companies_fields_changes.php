<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCompaniesFieldsChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            DB::statement("ALTER TABLE companies CHANGE COLUMN category_id category_id int DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN sector_id sector_id int DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN country_id country_id int DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN city_id city_id int DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN parent_id parent_id int DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN city_name city_name text DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN url_to_redirect url_to_redirect text DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN city_latitude city_latitude text DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN city_longtitude city_longtitude text DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN city_population city_population text DEFAULT NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN region region text DEFAULT NULL");
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
