<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppNewsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_news_type
    	Schema::create('app_news_type', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->string('title', 32)->nullable()->comment('名称');
    		$table->tinyInteger('icon_type')->default('1')->comment('アイコン');
    		$table->string('entry_url')->nullable()->comment('登録URL');
    		$table->tinyInteger('active_flag')->default('0')->comment('有効:1');
    		$table->dateTime('register_date')->nullable()->comment('作成日');
    		$table->dateTime('update_date')->nullable()->comment('更新日');
    		$table->dateTime('delete_date')->nullable()->comment('削除日');
    		$table->unsignedInteger('register_admin')->nullable()->comment('作成者');
    		$table->unsignedInteger('update_admin')->nullable()->comment('更新者');
    	});        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	//drop app_news_type
    	Schema::dropIfExists('app_news_type');
    }
}
