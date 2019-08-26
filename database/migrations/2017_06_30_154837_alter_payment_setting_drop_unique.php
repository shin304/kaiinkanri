<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentSettingDropUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_setting', function (Blueprint $table) {
            $table->dropUnique('payment_method_setting_item_name_unique');
            $table->dropUnique('payment_method_setting_item_display_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_setting', function (Blueprint $table) {
            $table->unique('item_name');
            $table->unique('item_display_name');
        });
    }
}
