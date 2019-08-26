<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagToMPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_plan', function (Blueprint $table) {
            $table->tinyInteger('display_in_demo')->default(1)->after('plan_amount')->nullable()->comment('1:表示 0:なし');
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
            $table->dropColumn('display_in_demo');
        });
    }
}
