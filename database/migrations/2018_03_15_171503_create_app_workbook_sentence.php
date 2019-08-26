<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWorkbookSentence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_workbook_sentence
    	Schema::create('app_workbook_sentence', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->unsignedInteger('subject_id')->nullable()->comment('教科ID');
    		$table->unsignedInteger('course_id')->nullable()->comment('科目ID');
    		$table->string('title')->nullable()->comment('タイトル');
    		$table->unsignedInteger('large_id')->nullable()->comment('大分類');
    		$table->unsignedInteger('middle_id')->nullable()->comment('中分類');
    		$table->unsignedInteger('small_id')->nullable()->comment('小分類');
    		$table->tinyInteger('question_regi_type')->nullable()->comment('設問形式:1=テキスト,2=ファイル');
    		$table->string('question_file')->nullable()->comment('設問ファイル');
    		$table->text('question_file_name')->nullable()->comment('設問ファイル名称');
    		$table->text('question_text')->nullable()->comment('設問テキスト');
    		$table->string('audio_file')->nullable()->comment('音声ファイル');
    		$table->text('audio_file_name')->nullable()->comment('音声ファイル名称');
    		$table->tinyInteger('description_regi_type')->nullable()->comment('解説形式:1=テキスト,2=ファイル');
    		$table->string('description_file')->nullable()->comment('解説ファイル');
    		$table->text('description_file_name')->nullable()->comment('解説ファイル名称');
    		$table->text('description_text')->nullable()->comment('解説テキスト');
    		$table->string('choices_file')->nullable()->comment('選択肢ファイル');
    		$table->text('choices_file_name')->nullable()->comment('選択肢ファイル名称');
    		$table->text('choices_text')->nullable()->comment('選択肢テキスト');
    		$table->text('choices')->nullable()->comment('選択肢');
    		$table->string('c_answer')->nullable()->comment('正答');
    		$table->tinyInteger('difficulty')->nullable()->comment('難易度:3=易しい,5=ふつう,7=難しい');
    		$table->tinyInteger('p_status')->default('0')->comment('公開:1');
    		$table->tinyInteger('allow_measure')->default('0')->comment('評価:1=する');
    		$table->tinyInteger('allow_secondary_use')->default('0')->comment('2次利用:1=許可');
    		$table->text('create_by')->nullable()->comment('作成者');
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
    	//drop app_workbook_sentence
    	Schema::dropIfExists('app_workbook_sentence');
    }
}
