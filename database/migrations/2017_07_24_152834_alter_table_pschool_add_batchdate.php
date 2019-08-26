<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePschoolAddBatchdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->tinyInteger('invoice_batch_date')->default(1)->comment('毎月請求処理バッチを実行する日');
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
            $table->dropColumn('invoice_batch_date');
        });
    }
}
