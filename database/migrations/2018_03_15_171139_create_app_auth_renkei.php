<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppAuthRenkei extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_auth_renkei
    	Schema::create('app_auth_renkei', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('member_id')->nullable()->comment('会員ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->unsignedInteger('student_id')->nullable()->comment('生徒ID');
    		$table->string('pschool_code', 6)->nullable()->comment('塾コード');
    		$table->string('pschool_mailaddress')->nullable()->comment('塾メールアドレス');
    		$table->string('auth_key', 6)->nullable()->comment('認証キー');
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
    	//drop app_auth_renkei
    	Schema::dropIfExists('app_auth_renkei');
    }
}
