<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCourseAddPersonInCharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->string ( 'person_in_charge1', 100)->nullable()->comment('担当者１');
            $table->string ( 'person_in_charge2', 100)->nullable()->comment('担当者２');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropColumn('person_in_charge1');
            $table->dropColumn('person_in_charge2');
        });
    }
}
