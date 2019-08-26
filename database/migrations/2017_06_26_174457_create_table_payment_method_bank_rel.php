<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethodBankRel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_bank_rel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->unsigned()->comment('施設（pschool）に参照する');
            $table->integer('payment_method_id')->unsigned()->comment('支払方法テブールに参照する');
            $table->integer('bank_account_id')->unsigned()->comment('銀行アカウントテーブルに参照する');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();

            $table->unique(['pschool_id','payment_method_id']);
        });
        DB::statement("ALTER TABLE `payment_method_bank_rel` comment '施設で支払方法に対して、銀行を使う時連携テーブルになる'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_bank_rel');
    }
}
