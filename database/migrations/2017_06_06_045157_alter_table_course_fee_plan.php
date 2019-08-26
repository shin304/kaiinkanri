<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCourseFeePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_fee_plan', function (Blueprint $table) {
            $table->tinyInteger ( 'payment_unit')->nullable()->after('fee')->default(1)->comment('選択リスト追加: 1:一人当たり, 2:全員で');
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
            $table->dropColumn('payment_unit');
        });
    }
}
