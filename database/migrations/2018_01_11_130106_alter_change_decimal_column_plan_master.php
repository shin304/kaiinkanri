<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChangeDecimalColumnPlanMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_plan', function (Blueprint $table) {
            $table->decimal('plan_amount',10,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_plan', function (Blueprint $table) {
            $table->decimal('plan_amount',8,2)->change();
        });
    }
}
