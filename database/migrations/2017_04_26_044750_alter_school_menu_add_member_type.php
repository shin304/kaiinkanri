<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSchoolMenuAddMemberType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_menu', function (Blueprint $table) {
            $table->tinyInteger('member_type')->default(1)->comment = "1:DANTAI, 2:STAFF, 3:COACH";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_menu', function (Blueprint $table) {
            $table->dropColumn('member_type');
        });
    }
}
