<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLoginAccountUpdateUniqueKeySet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_account', function (Blueprint $table) {
            $table->dropUnique('login_account_pschool_id_login_id_unique');
            $table->unique(['login_id', 'pschool_id', 'delete_date'], 'login_account_pschool_id_login_id_delete_date_unique');
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
            $table->dropUnique('login_account_pschool_id_login_id_delete_date_unique');
            $table->unique(['login_id', 'pschool_id'], 'login_account_pschool_id_login_id_unique');
        });
    }
}
