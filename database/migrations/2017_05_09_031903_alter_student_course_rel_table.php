<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudentCourseRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'student_course_rel', function (Blueprint $table) {
            $table->tinyInteger('payment_method')->nullable()->comment('0:現金, 1:振込, 2:口座振替');
            $table->timestamp( 'payment_date' )->nullable();

        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_course_rel', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->timestamp( 'payment_date' )->nullable();
        });
    }
}
