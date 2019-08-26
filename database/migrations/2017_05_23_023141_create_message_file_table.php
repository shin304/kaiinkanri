<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'message_file', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->integer ( 'parent_id' )->unsigned()->default(0)->nullable();
            $table->integer ( 'bussiness_type_id' )->unsigned()->default(0)->nullable()->comment('業態テーブルに参照する、０：commonメッセージ');
            $table->integer ( 'pschool_id' )->unsigned()->default(0)->nullable()->comment('pschoolに参照する、０：masterメッセージ');
            $table->tinyInteger ( 'lang_code' )->unsigned()->default(2)->nullable()->comment('１：英語、２：日本語');
            $table->string ( 'message_file_name', 255)->nullable()->comment('メッセージファイル名');
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
        Schema::dropIfExists ( 'message_file' );
        
    }
}
