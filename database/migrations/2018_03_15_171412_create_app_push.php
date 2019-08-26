<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppPush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_push
    	Schema::create('app_push', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('member_id')->comment('会員ID');
    		$table->string('device_token')->nullable()->comment('デバイストークン');
    		$table->string('identifierForVendor')->nullable()->comment('ベンダー');
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
    	//drop app_push
    	Schema::dropIfExists('app_push');
    }
}
