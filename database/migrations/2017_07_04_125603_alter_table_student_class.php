<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStudentClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'student_class', function (Blueprint $table) {
            $table->tinyInteger('payment_method')->nullable()->comment('支払方法選択');
            $table->tinyInteger('number_of_payment')->nullable()->comment('支払回数：1回～12回、毎月：９９');
            $table->boolean('notices_mail_flag')->nullable()->default(0)->comment('事前通知');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_class', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('number_of_payment');
            $table->dropColumn('notices_mail_flag');
        });
    }
}
