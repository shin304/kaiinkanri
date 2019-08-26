<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppMemberWorkbookRel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_member_workbook_rel
    	Schema::create('app_member_workbook_rel', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('member_id')->nullable()->comment('会員ID');
    		$table->unsignedInteger('workbook_id')->nullable()->comment('問題集ID');
    		$table->unsignedInteger('buy_type')->nullable()->comment('購入タイプ:1=初期データ,2以降未定');
    		$table->dateTime('buy_datetime')->nullable()->comment('購入日');
    		$table->decimal('buy_price', 10, 2)->default('0')->comment('購入金額');
    		$table->dateTime('limit_datetime')->nullable()->comment('有効日');
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
    	//drop app_member_workbook_rel
    	Schema::dropIfExists('app_member_workbook_rel');
    }
}
