<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCompaniesChangeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // $table->text('description')->nullable()->change();
            // $table->text('short_description')->nullable()->change();
            // $table->text('looking_for')->nullable()->change();
            // $table->text('requirement')->nullable()->change();
            // $table->text('why_us')->nullable()->change();
            DB::statement("ALTER TABLE companies CHANGE COLUMN description description text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN short_description short_description text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN looking_for looking_for text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN requirement requirement text NULL");
            DB::statement("ALTER TABLE companies CHANGE COLUMN why_us why_us text NULL");
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
