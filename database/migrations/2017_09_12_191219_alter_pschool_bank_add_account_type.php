<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPschoolBankAddAccountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool_bank_account', function (Blueprint $table) {
            $table->tinyInteger('post_account_type')->nullable()->after('bank_account_name_kana')->comment('郵便局の勘定タイプ');
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
            $table->dropColumn('post_account_type');
        });
    }
}
