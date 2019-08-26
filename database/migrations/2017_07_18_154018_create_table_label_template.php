<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLabelTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'label_template', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->string ( 'name', 255 )->nullable()->comment('ラベルテンプレート名');
            $table->tinyInteger ( 'label_type' )->nullable()->comment ( '1.会員 2.請求先' );
            $table->string ( 'columns', 255 )->nullable ()->comment ( '設定したカラムリスト' );
            $table->boolean ( 'export_header' )->nullable()->comment('先頭行にヘッダー出力');
            $table->tinyInteger ( 'encode' )->nullable()->comment ( '1.SHIFT-JIS 2.UTF-8' );
            $table->timestamps ();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists ( 'label_template' );
    }
}
