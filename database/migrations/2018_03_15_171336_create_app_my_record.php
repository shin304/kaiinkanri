<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppMyRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_my_record
    	Schema::create('app_my_record', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->unsignedInteger('student_id')->nullable()->comment('生徒ID');
    		$table->unsignedInteger('member_id')->nullable()->comment('会員ID');
    		$table->unsignedInteger('workbook_id')->nullable()->comment('問題集ID');
    		$table->unsignedInteger('chapter_id')->nullable()->comment('問題集の章ID');
    		$table->dateTime('activity_start_datetime')->nullable()->comment('試験開始');
    		$table->dateTime('activity_end_datetime')->nullable()->comment('試験終了');
    		$table->unsignedInteger('activity_time')->nullable()->comment('試験時間');
    		$table->decimal('score', 10, 2)->nullable()->comment('点数');
    		$table->decimal('per_c_answer', 10, 2)->nullable()->comment('正答率');
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
    	//drop app_my_record
    	Schema::dropIfExists('app_my_record');
    }
}
