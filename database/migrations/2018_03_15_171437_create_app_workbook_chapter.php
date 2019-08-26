<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWorkbookChapter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_workbook_chapter
    	Schema::create('app_workbook_chapter', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('workbook_id')->nullable()->comment('問題集ID');
    		$table->unsignedInteger('subject_id')->nullable()->comment('教科ID');
    		$table->string('code')->nullable()->comment('ディレクトリ');
    		$table->string('title')->nullable()->comment('タイトル');
    		$table->string('subtitle')->nullable()->comment('サブタイトル');
    		$table->decimal('passing_average_score', 10, 2)->nullable()->comment('合格者平均点');
    		$table->decimal('passing_min_score', 10, 2)->nullable()->comment('合格者最低点');
    		$table->decimal('all_average_score', 10, 2)->nullable()->comment('平均点');
    		$table->unsignedInteger('exam_time')->nullable()->comment('試験時間');
    		$table->decimal('full_score', 10, 2)->nullable()->comment('満点');
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
    	//drop app_workbook_chapter
    	Schema::dropIfExists('app_workbook_chapter');
    }
}
