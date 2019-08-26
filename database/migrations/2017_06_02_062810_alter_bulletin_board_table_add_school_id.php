<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBulletinBoardTableAddSchoolId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulletin_board', function (Blueprint $table) {
            $table->integer ( 'pschool_id' )->unsigned()->nullable()->after ( 'id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulletin_board', function (Blueprint $table) {
            $table->dropColumn('pschool_id');
        });
    }
}
