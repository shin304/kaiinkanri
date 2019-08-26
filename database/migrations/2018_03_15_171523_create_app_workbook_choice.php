<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWorkbookChoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_workbook_choice
    	Schema::create('app_workbook_choice', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('sentence_id')->nullable()->comment('問題種の設問文ID');
    		$table->tinyInteger('choice_true')->default('0')->comment('正答:1');
    		$table->string('choice_mark', 16)->nullable()->comment('選択肢記号');
    		$table->text('choice_word')->nullable()->comment('選択肢文言');
    		$table->tinyInteger('choice_regi_type')->default('1')->comment('選択肢形式:1=テキスト,2=ファイル');
    		$table->string('choice_file')->nullable()->comment('選択肢ファイル');
    		$table->text('choice_file_name')->nullable()->comment('選択肢ファイル名称');
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
    	//drop app_workbook_choice
    	Schema::dropIfExists('app_workbook_choice');
    }
}
