<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMailMessageAddTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_message', function (Blueprint $table) {
            $table->integer('parent_id')->nullable()->change();
            $table->integer('target')->unsigned()->after('pschool_id')->nullable()->comment('1:請求先、２：会員、３：講師、４：スタッフ');
            $table->integer('target_id')->unsigned()->after('target')->nullable()->comment('該当する対象ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_message', function (Blueprint $table) {
            $table->dropColumn('target');
            $table->dropColumn('target_id');
        });
    }
}
