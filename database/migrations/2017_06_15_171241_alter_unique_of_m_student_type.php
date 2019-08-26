<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUniqueOfMStudentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_student_type', function (Blueprint $table) {
            $table->unique(['pschool_id','code'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_student_type', function (Blueprint $table) {
            $table->dropUnique(['pschool_id','code'])->change();
        });
    }
}
