<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->comment('pschoolの表を参照してください');
            $table->tinyInteger('type')->comment('1:会員管理,2:請求先');
            $table->string('action',100)->comment('操作名');
            $table->text('message')->comment('メッセージ');
            $table->timestamps();
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
        Schema::dropIfExists('history_log');
    }
}
