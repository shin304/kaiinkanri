<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentDataDropUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_data', function (Blueprint $table) {
            $table->dropUnique('payment_method_data_item_name_unique');
            $table->dropUnique('payment_method_data_item_value_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_data', function (Blueprint $table) {
            $table->unique('item_name');
            $table->unique('item_value');
        });
    }
}
