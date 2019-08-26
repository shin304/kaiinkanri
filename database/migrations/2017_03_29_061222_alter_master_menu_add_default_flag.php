<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMasterMenuAddDefaultFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_menu', function (Blueprint $table) {
            $table->tinyInteger('default_flag')->nullable()->default(0)->after('icon_url');
            $table->tinyInteger('sub_seq_no')->nullable()->after('default_flag');
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
            $table->dropColumn('default_flag');
            $table->dropColumn('sub_seq_no');
        });
    }
}
