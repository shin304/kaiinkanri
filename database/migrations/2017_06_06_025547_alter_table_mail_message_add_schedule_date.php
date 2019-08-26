<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMailMessageAddScheduleDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_message', function (Blueprint $table) {
            $table->dateTime ( 'schedule_date' )->after ( 'send_date' )->nullable()->comment( '送信予約日時' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'mail_message', function (Blueprint $table) {
            $table->dropColumn ( 'schedule_date' );
        });
    }
}
