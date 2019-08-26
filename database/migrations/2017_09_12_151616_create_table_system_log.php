<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSystemLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->unsigned()->comment('スクールID');
            $table->boolean('status')->nullable()->comment('状況.1:通知,2:エラー');
            $table->string('process', 100)->nullable()->comment('処理名.ex:請求処理');
            $table->text('message')->nullable()->comment('表示内容');
            $table->dateTime('register_date')->nullable()->comment('登録日時');
            $table->dateTime('update_date')->nullable()->comment('更新日時');
            $table->dateTime('delete_date')->nullable()->comment('削除日時');
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_log');
    }
}
