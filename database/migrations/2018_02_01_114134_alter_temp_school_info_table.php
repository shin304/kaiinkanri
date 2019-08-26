<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTempSchoolInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_school_info', function (Blueprint $table) {
            $table->string('building')->nullable()->change();
            $table->string('fax')->nullable()->change();
            $table->string('home_page')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_school_info', function (Blueprint $table) {
            $table->dropColumn('building');
            $table->dropColumn('fax');
            $table->dropColumn('home_page');
        });
    }
}
