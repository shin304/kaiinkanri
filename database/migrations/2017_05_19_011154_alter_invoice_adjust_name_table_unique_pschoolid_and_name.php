<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceAdjustNameTableUniquePschoolidAndName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'invoice_adjust_name', function (Blueprint $table) {
            $table->unique(['pschool_id', 'name']);
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_adjust_name', function (Blueprint $table) {
            $table->dropUnique(['pschool_id', 'name']);
        });
    }
}
