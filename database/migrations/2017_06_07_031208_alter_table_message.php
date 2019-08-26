<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'message', function (Blueprint $table) {
            $table->string ( 'message_key', 50)->nullable()->comment('画面内のメッセージkey')->change();
            $table->string ( 'screen_key', 50)->nullable()->comment('画面名のメッセージkey')->change();
            $table->unique( ['message_file_id', 'screen_key', 'message_key'], 'unique_index' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'message', function (Blueprint $table) {
            $table->dropUnique( 'unique_index' );
        } );
    }
}
