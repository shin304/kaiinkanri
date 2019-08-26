<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'mail_template', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->string ( 'name', 100 )->unique ();
            $table->integer ( 'mail_type' )->comment ( '1.イベント 2.プログラム 3.お知らせ送信　4.請求書' )->unsigned ();
            $table->string ( 'subject', 255 )->nullable ();
            $table->text ( 'body' )->nullable ();
            $table->text ( 'footer' )->nullable ();
            $table->timestamps ();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists ( 'mail_template' );
    }
}
