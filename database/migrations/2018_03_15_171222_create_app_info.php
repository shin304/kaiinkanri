<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_info
    	Schema::create('app_info', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->string('workbook_title')->nullable()->comment('問題集の名称');
    		$table->string('workbook_type_ids')->nullable()->comment('問題集種別のIDs(カンマ区切り)');
    		$table->string('news_title')->nullable()->comment('お知らせの名称');
    		$table->string('news_type_ids')->nullable()->comment('お知らせのIDs(カンマ区切り)');
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
    	//drop app_info
    	Schema::dropIfExists('app_info');
    }
}
