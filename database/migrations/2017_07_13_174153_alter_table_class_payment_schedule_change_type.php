<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClassPaymentScheduleChangeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'class_payment_schedule', function (Blueprint $table) {
            // schedule_date only month-day
            $table->string('schedule_date',25)->nullable()->comment('支払基準日')->change();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_payment_schedule', function (Blueprint $table) {
            $table->dropColumn('schedule_date');
        });
    }
}
