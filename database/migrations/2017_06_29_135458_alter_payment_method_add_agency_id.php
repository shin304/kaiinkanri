<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentMethodAddAgencyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method', function (Blueprint $table) {
            $table->integer('payment_agency_id')->after('code')->unsigned()->comment('収納代行会社テーブルに参照する');
            $table->dropUnique('payment_method_code_unique');
            $table->dropUnique('payment_method_name_unique');
            $table->unique(['code','payment_agency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method', function (Blueprint $table) {
            $table->dropUnique(['code','payment_agency_id']);
            $table->dropColumn('payment_agency_id');
        });
    }
}
