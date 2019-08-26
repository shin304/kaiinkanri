<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceAdjustNameRemoveUnsignedInitialFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_adjust_name', function (Blueprint $table) {
            $table->decimal('initial_fee',10,2)->unsigned(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_person_in_charge', function (Blueprint $table) {
            $table->decimal('initial_fee',10,2)->unsigned()->change();
        });
    }
}
