<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLoginAccountAddPschoolId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_account', function (Blueprint $table) {
            $table->integer('pschool_id')->unsigned()->nullable()->comment('施設ID');
            $table->unique(['pschool_id', 'login_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_account', function (Blueprint $table) {
            $table->dropColumn('pschool_id');
            $table->dropUnique(['pschool_id', 'login_id']);
        });
    }
}
