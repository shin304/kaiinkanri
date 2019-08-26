<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntryTableEditPaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->string('payment_method', 20)->nullable()->comment('支払方法・CASH:現金・振込,TRAN_RICOH:口座振替,CONV_RICOH:コンビニ決済,POST_RICOH:ゆうちょ振込,CRED_ZEUS:クレジットカード決済')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
}
