<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableJobsChangeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            // $table->text('description')->nullable()->change();
            // $table->text('short_description')->nullable()->change();
            // $table->text('looking_for')->nullable()->change();
            // $table->text('requirement')->nullable()->change();
            // $table->text('why_us')->nullable()->change();
            DB::statement("ALTER TABLE jobs CHANGE COLUMN description description text NULL");
            DB::statement("ALTER TABLE jobs CHANGE COLUMN about_company about_company text NULL");
            DB::statement("ALTER TABLE jobs CHANGE COLUMN benefits benefits text NULL");
            DB::statement("ALTER TABLE jobs CHANGE COLUMN requirement requirement text NULL");
            DB::statement("ALTER TABLE jobs CHANGE COLUMN why_us why_us text NULL");
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
