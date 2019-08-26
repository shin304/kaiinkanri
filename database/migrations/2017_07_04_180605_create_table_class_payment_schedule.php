<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClassPaymentSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_payment_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_class_id')->unsigned()->nullable()->comment('クラスで会員毎の料金ID');
            $table->tinyInteger('payment_times_no')->nullable()->comment('支払回数：1回～12回、毎月：９９');
            $table->timestamp('schedule_date')->nullable()->comment('支払基準日');
            $table->integer('schedule_fee')->nullable()->comment('金額');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('class_payment_schedule');
    }
}
