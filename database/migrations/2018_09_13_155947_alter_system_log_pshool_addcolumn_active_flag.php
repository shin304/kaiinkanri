<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLogPshoolAddcolumnActiveFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add coloumn active_flag in table system_log_pshool
        Schema::table('system_log_pschool', function (Blueprint $table) {
            $table->integer('active_flag')->nullable()->comment('null:no-active、1:active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_log_pschool', function (Blueprint $table) {
            $table->dropColumn('active_flag');
        });
    }
}
