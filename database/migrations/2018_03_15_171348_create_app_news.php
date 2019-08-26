<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_news
    	Schema::create('app_news', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->unsignedInteger('news_type_id')->default('1')->comment('お知らせ種別ID');
    		$table->string('title')->nullable()->comment('タイトル');
    		$table->string('subtitle')->nullable()->comment('サブタイトル');
    		$table->string('content_title')->nullable()->comment('内容タイトル');
    		$table->text('content')->nullable()->comment('内容');
    		$table->date('disp_date')->nullable()->comment('表示日');
    		$table->dateTime('publish_date_from')->nullable()->comment('公開開始日');
    		$table->dateTime('publish_date_to')->nullable()->comment('公開終了日');
    		$table->string('link_url')->nullable()->comment('HPのリンク');
    		$table->string('link_pdf')->nullable()->comment('pdfのリンク');
    		$table->string('file_pdf')->nullable()->comment('pdfのファイル名称');
    		$table->tinyInteger('has_notification')->default('1')->comment('通知:1');
    		$table->dateTime('notification_datetime')->nullable()->comment('通知日');
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
    	//drop app_news
    	Schema::dropIfExists('app_news');
    }
}
