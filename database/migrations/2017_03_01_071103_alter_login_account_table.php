<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLoginAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_account', function (Blueprint $table) {
            $table->integer('lang_code')->default(1)->comment('1:英語 2:日本語');
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
            $table->dropColumn('lang_code');
        });
    }
}
