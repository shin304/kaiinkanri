<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInvoiceDebit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_debit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_header_id')->comment('自身の債務ID');
            $table->string('invoice_year_month',10)->comment('自分の借金月');
            $table->integer('invoice_debit_id');
            $table->string('debit_year_month',10);
            $table->integer('amount')->comment('デビット額');
            $table->date('due_date')->comment('支払期限');
            $table->tinyInteger('status')->comment('0:処理 1:入金済');

            $table->dateTime('register_date')->nullable()->comment('登録日時');
            $table->dateTime('update_date')->nullable()->comment('更新日時');
            $table->dateTime('delete_date')->nullable()->comment('削除日時');
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_debit');
    }
}
