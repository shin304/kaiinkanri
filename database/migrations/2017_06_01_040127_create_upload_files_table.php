<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_files', function (Blueprint $table) {
            $table->increments( 'id' );
            $table->integer( 'category_code' )->nullable()->comment('カテゴリ 1:掲示板　2:...');
            $table->integer( 'target_id' )->unsigned()->nullable()->comment('対象のid (掲示板ID,...)');
            $table->string( 'file_path' , 255)->nullable()->comment('ファイルのパス');
            $table->string( 'real_file_name' , 255)->nullable()->comment('実ファイル名');
            $table->text( 'disp_file_name' )->nullable()->comment('表示ファイル名');
            $table->timestamps();
            $table->integer ( 'register_admin' )->unsigned()->nullable();
            $table->integer ( 'update_admin' )->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
    }
}
