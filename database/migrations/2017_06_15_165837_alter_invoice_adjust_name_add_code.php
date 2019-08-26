<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceAdjustNameAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_adjust_name', function (Blueprint $table) {
            $table->string('code',50)->after('name')->comment('金額コードを調整する');
            $table->unique(['pschool_id','code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_adjust_name', function (Blueprint $table) {
            $table->dropUnique(['pschool_id','code']);
            $table->dropColumn('code');
        });
    }
}
