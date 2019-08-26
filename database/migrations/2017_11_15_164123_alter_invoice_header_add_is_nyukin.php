<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceHeaderAddIsNyukin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->tinyInteger('is_nyukin')->nullable()->after('invoice_type');
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
            $table->dropColumn('is_nyukin');
        });
    }
}
