<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64)->unique()->comment('支払方法「口座振替、コンビニ決済…」');
            $table->string('code',64)->unique()->comment('支払方法コード');
            $table->tinyInteger('is_using_bank')->comment('１：銀行を使う、０：使わない');
            $table->text('description')->nullable()->comment('説明');
            $table->integer('sort_no')->nullable()->comment('表示順番');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        });
        DB::statement("ALTER TABLE `payment_method` comment '支払法情報'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method');
    }
}
