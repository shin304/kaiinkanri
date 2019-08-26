<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMStudentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_student_type', function (Blueprint $table) {
            $table->string('code',50)->unique()->after('pschool_id')->comment('タイプcolumnの代わりに表示');
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
            $table->dropColumn('code');
        });
    }
}
