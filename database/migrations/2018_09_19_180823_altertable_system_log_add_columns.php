<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltertableSystemLogAddColumns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::table('system_log', function (Blueprint $table) {

            $table->date('start_calendar_dis')->nullable()->after('start_date');
            $table->date('end_calendar_dis')->nullable()->after('start_calendar_dis');
            $table->dateTime('view_date')->nullable()->after('end_calendar_dis');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::table('system_log', function (Blueprint $table) {

            $table->dropColumn('start_calendar_dis');
            $table->dropColumn('end_calendar_dis');
            $table->dropColumn('view_date');
        });
    }
}
