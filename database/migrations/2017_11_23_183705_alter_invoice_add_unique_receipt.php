<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceAddUniqueReceipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->string('receipt_number')->nullable();
            $table->tinyInteger('receipt_count')->default("0");
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
            $table->dropColumn('receipt_number');
            $table->dropColumn('receipt_count');
        });
    }
}
