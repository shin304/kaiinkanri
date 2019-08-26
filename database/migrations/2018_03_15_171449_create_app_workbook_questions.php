<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWorkbookQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_workbook_questions
    	Schema::create('app_workbook_questions', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('workbook_id')->nullable()->comment('問題集ID');
    		$table->unsignedInteger('chapter_id')->nullable()->comment('問題集の章ID');
    		$table->string('code')->nullable()->comment('ディレクトリ');
    		$table->unsignedInteger('sequence_no')->nullable()->comment('並び順');
    		$table->unsignedInteger('question_type')->nullable()->comment('種別:1=リスニング');
    		$table->string('title')->nullable()->comment('タイトル');
    		$table->text('tags')->nullable()->comment('タグ(カンマ区切り)');
    		$table->text('choices')->nullable()->comment('選択肢(カンマ区切り)');
    		$table->string('c_answer')->nullable()->comment('正答');
    		$table->unsignedInteger('full_score')->nullable()->comment('点数');
    		$table->decimal('per_c_answer', 10, 2)->nullable()->comment('正答率');
    		$table->unsignedInteger('sentence_id')->nullable()->comment('設問文ID');
    		$table->string('filepath')->nullable()->comment('ファイル');
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
    	//drop app_workbook_questions
    	Schema::dropIfExists('app_workbook_questions');
    }
}
