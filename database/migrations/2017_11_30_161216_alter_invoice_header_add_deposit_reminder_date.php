<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceHeaderAddDepositReminderDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->dateTime('deposit_reminder_date')->nullable()->comment('入金催促状メール送信日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->drop('deposit_reminder_date');
        });
    }
}
