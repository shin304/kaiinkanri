<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLogAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('system_log', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('message');
            $table->date('end_date')->nullable()->after('message');
            

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
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');

        });
    }
}
