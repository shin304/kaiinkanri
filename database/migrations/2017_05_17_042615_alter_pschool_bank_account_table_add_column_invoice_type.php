<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPschoolBankAccountTableAddColumnInvoiceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'pschool_bank_account', function (Blueprint $table) {
            $table->tinyInteger( 'invoice_type' )->after ( 'consignor_name' )->unsigned()->comment('0=現金 1=振込 2=口座振替')->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pschool_bank_account', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });
    }
}
