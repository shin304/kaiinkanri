<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemLogPschool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create table sysem_log_pschool
        Schema::create('system_log_pschool', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('notify_id')->unsigned()->nullable();
            $table->bigInteger('pschool_id')->unsigned()->nullable();
            $table->dateTime('register_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->dateTime('delete_date')->nullable();
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
        //Drop table system_log_pschool
        Schema::dropIfExists('system_log_pschool');
    }
}
