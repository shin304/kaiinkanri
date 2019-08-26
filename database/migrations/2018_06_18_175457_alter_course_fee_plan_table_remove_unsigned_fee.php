<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCourseFeePlanTableRemoveUnsignedFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_fee_plan', function (Blueprint $table) {
            $table->decimal('fee',10,2)->unsigned(false)->change();
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
            $table->decimal('fee',10,2)->unsigned()->change();
        });
    }
}
