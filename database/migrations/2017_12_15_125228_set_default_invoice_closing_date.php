<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultInvoiceClosingDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->integer('invoice_closing_date')->default(20)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->integer('invoice_closing_date')->default(0)->change();
        });
    }
}
