<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClosingDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_day', function (Blueprint $table) {
            $table->integer('payment_agency_id')->unsigned()->nullable()->comment('収納代行会社のid')->after('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('closing_day', function (Blueprint $table) {
            $table->dropColumn('payment_agency_id');
        });
    }
}
