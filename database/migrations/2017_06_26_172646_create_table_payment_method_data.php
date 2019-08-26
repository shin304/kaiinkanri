<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethodData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->unsigned()->comment('施設（pschool）に参照する');
            $table->integer('payment_method_id')->unsigned()->comment('支払方法テブールに参照する');
            $table->integer('payment_method_setting_id')->unsigned()->comment('支払方法の項目設定テーブルに参照する');
            $table->string('item_name',64)->unique()->comment('項目名（プログラムで使う）');
            $table->string('item_value',64)->unique()->comment('項目の値');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();

            $table->unique(['pschool_id','payment_method_setting_id','item_name'],"pschool_and_method_is_unique");
        });
        DB::statement("ALTER TABLE `payment_method_data` comment '施設で支払方法に対して各設定した項目の値を持っているテブール'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_data');
    }
}
