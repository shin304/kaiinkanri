<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPschoolBankAddDefaultAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool_bank_account', function (Blueprint $table) {
            $table->tinyInteger('is_default_account')->default(0)->after('pschool_id')->comment('1：デフォルトアカウント; 0：なし');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pschool_bank_account', function (Blueprint $table) {
            $table->dropColumn('is_default_account');
        });
    }
}
