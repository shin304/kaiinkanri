<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCourseAddLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'course', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->change();
            // $table->dateTime('close_date')->nullable()->after('start_date')->change();
            $table->tinyInteger('send_mail_flag')->comment('ON/OFF送信メール機能')->nullable()->default(1);
            $table->text ( 'course_location')->nullable()->comment('開催場所');

        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropColumn('start_date');
            // $table->dropColumn('close_date');
            $table->dropColumn('send_mail_flag');
            $table->dropColumn('course_location');
            
        });
    }
}
