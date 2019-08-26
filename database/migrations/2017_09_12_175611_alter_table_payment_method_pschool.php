<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePaymentMethodPschool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_pschool', function (Blueprint $table) {
            $table->integer('sort_no')->nullable()->comment('表示順番');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_pschool', function (Blueprint $table) {
            $table->dropColumn('sort_no');
        });
    }
}
