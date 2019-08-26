<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->comment('pschoolの表を参照してください');
            $table->tinyInteger('type')->comment('1:会員管理,2:クラス,3:イベント,4:プログラム,5:請求先,6:先生');
            $table->string('name',100)->comment('表示名');
            $table->string('code',50)->comment('コード');
            $table->unique(['pschool_id','type','code']);
            $table->unique(['pschool_id','type','name']);
            $table->integer('sort_no')->comment('注文番号');
            $table->tinyInteger('active_flag')->comment('アクティブフラグ');
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
        Schema::dropIfExists('additional_category');
    }
}
