<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLoginAccountAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_account', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->datetime('time_of_code')->nullable();
            $table->string('password_tmp')->nullable();
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
            $table->dropColumn('code');
            $table->dropColumn('time_of_code');
            $table->dropColumn('password_tmp');
        });
    }
}
