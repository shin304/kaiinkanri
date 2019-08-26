<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHoliday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute')->nullable()->comment('属性');
            $table->integer('year')->nullable()->comment('年');
            $table->integer('month')->nullable()->comment('月');
            $table->integer('day')->nullable()->comment('日');
            $table->date('y_m_d')->nullable()->comment('年月日');
            $table->string('holiday_name', 255)->nullable()->comment('休日名称');
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
        Schema::drop('holiday');
    }
}
