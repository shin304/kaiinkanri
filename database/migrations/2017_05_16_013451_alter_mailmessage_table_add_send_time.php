<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMailmessageTableAddSendTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'mail_message', function (Blueprint $table) {
            $table->dateTime ( 'send_date' )->after ( 'last_refer_date' )->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'mail_message', function (Blueprint $table) {
            $table->dropColumn ( 'send_date' );
        } );
    }
}
