<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'course', function (Blueprint $table) {
            $table->string('mail_subject', 255)->nullable();
            $table->timestamp('recruitment_start')->nullable();
            $table->timestamp('recruitment_finish')->nullable();
            $table->boolean('schedule_flag')->nullable()->default(0);
            $table->timestamp('schedule_date')->nullable();
            $table->tinyInteger('payment_method')->comment('1:現金・振込, 2:口座振替, 3:両方')->nullable();
            $table->timestamp( 'payment_due_date' )->nullable();
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
            $table->dropColumn('mail_subject');
            $table->dropColumn('recruitment_start');
            $table->dropColumn('recruitment_finish');
            $table->dropColumn('schedule_flag');
            $table->dropColumn('schedule_date');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_due_date');
        });
    }
}
