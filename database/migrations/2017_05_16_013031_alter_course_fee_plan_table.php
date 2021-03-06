<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCourseFeePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'course_fee_plan', function (Blueprint $table) {
            $table->integer( 'student_type_id' )->nullable()->after('attend_times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_fee_plan', function (Blueprint $table) {
            $table->dropColumn('student_type_id');
        });
    }
}
