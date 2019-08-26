<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterConsignorTableFieldNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consignor', function (Blueprint $table) {
            $table->integer( 'withdrawal_day1')->nullable ()->change();
            $table->integer( 'withdrawal_day2')->nullable ()->change();
            $table->integer( 'withdrawal_day3')->nullable ()->change();
            $table->integer( 'withdrawal_day4')->nullable ()->change();
            $table->integer( 'withdrawal_day5')->nullable ()->change();
            $table->dateTime( 'register_date')->nullable ()->change();
            $table->dateTime( 'update_date')->nullable ()->change();
            $table->dateTime( 'delete_date')->nullable ()->change();
            $table->integer( 'register_admin')->nullable ()->change();
            $table->integer( 'update_admin')->nullable ()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consignor', function (Blueprint $table) {
            $table->dropColumn('withdrawal_day1');
            $table->dropColumn('withdrawal_day2');
            $table->dropColumn('withdrawal_day3');
            $table->dropColumn('withdrawal_day4');
            $table->dropColumn('withdrawal_day5');
            $table->dropColumn('register_date');
            $table->dropColumn('update_date');
            $table->dropColumn('delete_date');
            $table->dropColumn('update_admin');
            $table->dropColumn('update_admin'); 
        });
    }
}
