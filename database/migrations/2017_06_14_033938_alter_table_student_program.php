<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStudentProgram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_program', function (Blueprint $table) {
            $table->tinyInteger('is_received')->unsigned()->after('pschool_id')->nullable();
            $table->tinyInteger('payment_method')->nullable()->comment('0:現金, 1:振込, 2:口座振替');
            $table->timestamp( 'payment_date' )->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_program', function (Blueprint $table) {
            $table->dropColumn('is_received');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_date');
        });
    }
}
