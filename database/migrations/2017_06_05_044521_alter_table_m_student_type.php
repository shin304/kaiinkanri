<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMStudentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_student_type', function (Blueprint $table) {
            $table->text ( 'remark')->nullable()->after('required_fee')->comment('備考');
            $table->tinyInteger ( 'payment_unit')->nullable()->after('remark')->default(0)->comment('選択リスト追加: 1:一人当たり, 2:全員で');
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
            $table->dropColumn('remark');
            $table->dropColumn('payment_unit');
        });
    }
}
