<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProgramFeePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'program_fee_plan', function (Blueprint $table) {
            $table->integer( 'student_type_id' )->nullable()->after('attend_times');
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
        Schema::table('program_fee_plan', function (Blueprint $table) {
            $table->dropColumn('student_type_id');
            $table->dropColumn('payment_unit');
            
        });
    }
}
