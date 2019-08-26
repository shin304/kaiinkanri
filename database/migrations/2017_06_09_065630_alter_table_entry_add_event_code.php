<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEntryAddEventCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->string ( 'code', 50)->nullable()->comment('イベントコードの会員');
            $table->dateTime ( 'enter_date')->nullable()->comment( '参加日時' );
            $table->dateTime ( 'invoice_month')->nullable()->comment('請求月');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('enter_date');
            $table->dropColumn('invoice_month');
        });
    }
}
