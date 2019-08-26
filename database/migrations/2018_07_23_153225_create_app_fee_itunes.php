<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppFeeItunes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		//create app_fee
    	Schema::create('app_fee_itunes', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';

    		$table->increments('id')->comment('ID');
    		$table->string('title')->nullable()->comment('名称');
    		$table->integer('price')->default('0')->comment('価格');
    		$table->tinyInteger('fee_type')->default('3')->comment('課金タイプ:1=消耗型,2=非消耗型,3=更新登録,4=非更新登録');
			$table->string('reference_code')->nullable()->comment('itunes connectの参照名');
			$table->string('apple_code')->nullable()->comment('itunes connectのAppleID');
    		$table->string('itunes_code')->nullable()->comment('itunes connectの製品ID');
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
		//drop app_fee
    	Schema::dropIfExists('app_fee_itunes');
    }
}
