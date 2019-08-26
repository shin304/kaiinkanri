<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStudentAddRepresentativeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->string('representative_name', 255)->nullable()->comment('代表者名');
            $table->string('representative_name_kana', 255)->nullable()->comment('代表者名フリガナ');
            $table->string('representative_position', 64)->nullable()->comment('代表者役職');
            $table->string('representative_email', 255)->nullable()->comment('代表者EMAIL');
            $table->string('representative_tel', 15)->nullable()->comment('代表者TEL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('representative_name');
            $table->dropColumn('representative_name_kana');
            $table->dropColumn('representative_position');
            $table->dropColumn('representative_email');
            $table->dropColumn('representative_tel');
        });
    }
}
