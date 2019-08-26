<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBroadcastmailTableStaffFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'broadcast_mail', function (Blueprint $table) {
        
            $table->integer ( 'staff_flag' )->after ( 'teacher_flag' )->nullable()->change();
            $table->dateTime ( 'time_send' )->after ( 'staff_flag' )->nullable()->change();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
