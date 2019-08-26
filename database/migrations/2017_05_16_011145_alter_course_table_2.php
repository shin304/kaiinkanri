<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCourseTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'course', function (Blueprint $table) {
            $table->timestamp( 'mail_send_date' )->nullable();
            $table->integer('member_capacity')->nullable();
            $table->integer('non_member_capacity')->nullable();
            $table->boolean('application_deadline')->nullable()->default(0);
            $table->tinyInteger('fee_type')->comment('1:会員種別による料金設定, 2:回数による料金設定')->nullable();
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
            $table->dropColumn('mail_send_date');
            $table->dropColumn('member_capacity');
            $table->dropColumn('non_member_capacity');
            $table->dropColumn('application_deadline');
            $table->dropColumn('fee_type');
        });
    }
}
