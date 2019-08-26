<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClassPaymentSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'class_payment_schedule', function (Blueprint $table) {
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
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
            $table->dropColumn('register_admin');
            $table->dropColumn('update_admin');
        });
    }
}
