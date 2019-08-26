<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePschool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->integer('payment_agency_id')->nullable()->comment('収納代行会社に参照する');
            $table->string('kakuin_path',255)->nullable()->comment('角印画像');
            $table->tinyInteger('currency_decimal_point')->nullable()->default(0)->comment('通貨小数点位置設定');
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
            $table->dropColumn('payment_agency_id');
            $table->dropColumn('kakuin_path');
            $table->dropColumn('currency_decimal_point');
        });
    }
}
