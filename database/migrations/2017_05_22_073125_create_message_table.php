<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'message', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->integer ( 'message_file_id' )->unsigned()->default(0)->nullable()->comment('メッセージファイルID');
            $table->string ( 'screen_key', 255)->nullable()->comment('画面名のメッセージkey');
            $table->string ( 'screen_value', 255)->nullable()->comment('画面名のメッセージvalue');
            $table->string ( 'message_key', 255)->nullable()->comment('画面内のメッセージkey');
            $table->string ( 'message_value', 255)->nullable()->comment('画面内のメッセージvalue');
            $table->text ( 'comment')->nullable()->comment('メッセージの説明');
            $table->timestamps();
            $table->integer ( 'register_admin' )->unsigned()->nullable();
            $table->integer ( 'update_admin' )->unsigned()->nullable();

        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists ( 'message' );
        
    }
}
