<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRoutinePaymentAddStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routine_payment', function (Blueprint $table) {
            $table->boolean('data_div')->comment('1:クラス 2:イベント 3:保護者　4:プログラム 5:会員')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routine_payment', function (Blueprint $table) {
            $table->boolean('data_div')->comment('1:クラス 2:イベント 3:保護者　4:プログラム')->change();
        });
    }
}
