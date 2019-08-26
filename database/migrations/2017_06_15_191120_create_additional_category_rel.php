<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalCategoryRel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_category_rel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->comment('pschoolの表を参照してください');
            $table->integer('additional_category_id')->comment('additional_category_idに参照する項目');
            $table->tinyInteger('type')->comment('1:会員管理,2:クラス,3:イベント,4:プログラム');
            $table->integer('related_id')->comment('タイプにより、このIDはそのテーブルのIDに関わり');
            $table->text('value')->comment('項目の内容');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_category_rel');
    }
}
