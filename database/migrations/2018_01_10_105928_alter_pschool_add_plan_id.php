<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPschoolAddPlanId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->integer('m_plan_id')->nullable();
            $table->integer('limit_number_register')->nullable();
            $table->integer('limit_number_active')->nullable();
            $table->dateTime('valid_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pschool', function (Blueprint $table) {
            $table->dropColumn('m_plan_id');
            $table->dropColumn('limit_number_register');
            $table->dropColumn('limit_number_active');
            $table->dropColumn('valid_date');
        });
    }
}
