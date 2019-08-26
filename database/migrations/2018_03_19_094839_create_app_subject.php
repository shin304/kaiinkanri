<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_subject
    	Schema::create('app_subject', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->string('name')->nullable()->comment('教科名称');
    		$table->unsignedInteger('sequence_no')->nullable()->comment('並び順');
    		$table->tinyInteger('active_flag')->default('1')->comment('有効:1');
    		$table->unsignedInteger('m_tag')->nullable()->comment('旧教科ID');
    		$table->unsignedInteger('m_tag_subject_id')->nullable()->comment('旧教科ID');
    		$table->unsignedInteger('s_subject_id')->nullable()->comment('旧教科ID');
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
    	//drop app_subject
    	Schema::dropIfExists('app_subject');
    }
}
