<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLogAddColumnsUpdade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('system_log', function (Blueprint $table) {
            $table->integer('calendar_flag')->nullable()->after('end_date');
            $table->string('calendar_color')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_log', function (Blueprint $table) {
            $table->dropColumn('calendar_flag');
            $table->dropColumn('calendar_color');

        });
    }
}
