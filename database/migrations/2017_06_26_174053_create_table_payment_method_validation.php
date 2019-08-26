<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethodValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_validation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_method_setting_id')->unsigned()->comment('支払方法の項目設定テーブルに参照する');
            $table->string('rule',64)->comment('バリデーションルール');
            $table->string('message',255)->comment('バリデーションのメッセージ');
            $table->text('description')->nullable()->comment('説明');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();

            $table->unique(['payment_method_setting_id','rule']);
        });
        DB::statement("ALTER TABLE `payment_method_validation` comment '支払方法に対して設定した項目バリデーション情報'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_validation');
    }
}
