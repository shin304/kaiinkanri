<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulletinBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulletin_board', function (Blueprint $table) {
            $table->increments( 'id' );
            $table->string( 'title' , 255)->nullable()->comment('一覧表示用タイトル');
            $table->text( 'message' )->nullable()->comment('HTML対応メール');
            $table->date( 'start_date' )->nullable()->comment('掲載開始日');
            $table->date( 'finish_date' )->nullable()->comment('掲載終了日');
            $table->boolean( 'calendar_flag' )->default(false)->comment('カレンダーへの表示');
            $table->string( 'calendar_color' , 64)->default ( '#808080' )->comment('カレンダー表示色');
            $table->string( 'target' , 32)->default ( '0,0,0' )->comment('掲載対象 1がON スタッフ,講師,その他');
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
        Schema::dropIfExists('bulletin_board');
    }
}
