<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppFeePolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_fee_policy
    	Schema::create('app_fee_policy', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->unsignedInteger('app_fee_id')->nullable()->comment('有料コースID');
    		$table->string('title')->nullable()->comment('名称');
    		$table->text('policy')->nullable()->comment('規約文');
    		$table->tinyInteger('public_flag')->default('0')->comment('公開:1');
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
    	//drop app_fee_policy
    	Schema::dropIfExists('app_fee_policy');
    }
}
