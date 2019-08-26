<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_member
    	Schema::create('app_member', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->string('login_id')->nullable()->comment('ログインID');
    		$table->string('login_pw')->nullable()->comment('パスワード');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->string('pschool_code', 6)->nullable()->comment('塾コード');
    		$table->string('pschool_mailaddress')->nullable()->comment('塾メールアドレス');
    		$table->unsignedInteger('student_id')->nullable()->comment('生徒ID');
    		$table->string('mailaddress')->nullable()->comment('メールアドレス');
    		$table->string('member_name')->nullable()->comment('氏名');
    		$table->string('nickname')->nullable()->comment('ニックネーム');
    		$table->date('birthday')->nullable()->comment('誕生日');
    		$table->tinyInteger('school_type')->nullable()->comment('学校:1=中学,2=高校,3=大学');
    		$table->tinyInteger('school_year')->nullable()->comment('学年');
    		$table->unsignedInteger('industry_type_id')->nullable()->comment('業種ID');
    		$table->unsignedInteger('employees_type_id')->nullable()->comment('従業員数ID');
    		$table->tinyInteger('sex')->nullable()->comment('性別:1=男,2=女');
    		$table->string('coach_mailaddress')->nullable()->comment('保護者メールアドレス');
    		$table->unsignedInteger('pref_id')->nullable()->comment('住所の都道府県ID');
    		$table->unsignedInteger('city_id')->nullable()->comment('住所の市区町村ID');
    		$table->tinyInteger('active_flag')->default('0')->comment('有効:1');
    		$table->tinyInteger('status')->default('1')->comment('状態');
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
    	//drop app_member
    	Schema::dropIfExists('app_member');
    }
}
