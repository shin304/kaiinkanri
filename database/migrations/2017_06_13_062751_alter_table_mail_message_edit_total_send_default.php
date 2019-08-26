<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMailMessageEditTotalSendDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_message', function (Blueprint $table) {
            $table->integer('total_send')->nullable()->after('schedule_date')->default(0)->comment('送信回数')->change();
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
            $table->dropColumn ( 'total_send' );
        });
    }
}
