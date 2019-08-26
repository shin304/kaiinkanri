<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableInvoiceItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_item', function (Blueprint $table) {
            $table->tinyInteger('monthly_billing')->after('unit_price')->comment('請求月種別 0:毎月 1:毎月以外');
            $table->tinyInteger('payment_method')->after('monthly_billing')->comment('親に基づく支払方法');
            $table->date('due_date')->after('payment_method')->comment('支払期限日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_item', function (Blueprint $table) {
            $table->dropColumn('monthly_billing');
            $table->dropColumn('payment_method');
            $table->dropColumn('due_date');
        });
    }
}
