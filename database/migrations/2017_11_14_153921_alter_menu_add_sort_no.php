<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMenuAddSortNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_menu', function (Blueprint $table) {
            $table->tinyInteger('sort_no')->nullable()->after('default_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_menu', function (Blueprint $table) {
            $table->dropColumn('sort_no');
        });
    }
}
