<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWorkbook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create app_workbook
    	Schema::create('app_workbook', function (Blueprint $table){
    		$table->engine = 'InnoDB';
    		$table->charset = 'utf8';
    		 
    		$table->increments('id')->comment('ID');
    		$table->unsignedInteger('info_id')->nullable()->comment('利用者ID');
    		$table->unsignedInteger('pschool_id')->nullable()->comment('塾ID');
    		$table->string('code')->nullable()->comment('ディレクトリ');
    		$table->string('title')->nullable()->comment('タイトル');
    		$table->string('subtitle')->nullable()->comment('サブタイトル');
    		$table->text('detail_text')->nullable()->comment('詳細');
    		$table->tinyInteger('is_public')->default('0')->comment('作成済:1');
    		$table->tinyInteger('is_preinstalled')->default('0')->comment('プリインストール:1');
    		$table->tinyInteger('is_free')->default('0')->comment('無料:1');
    		$table->decimal('price', 10, 2)->default('0')->comment('金額');
    		$table->string('ios_product_id')->nullable()->comment('apple');
    		$table->string('android_product_id')->nullable()->comment('android');
    		$table->decimal('passing_average_score', 10, 2)->nullable()->comment('合格者平均点');
    		$table->decimal('passing_min_score', 10, 2)->nullable()->comment('合格者最低点');
    		$table->decimal('all_average_score', 10, 2)->nullable()->comment('平均点');
    		$table->tinyInteger('icon_type')->default('1')->comment('アイコン種類');
    		$table->unsignedInteger('workbook_type_id')->default('1')->comment('問題集の種別ID');
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
    	//drop app_workbook
    	Schema::dropIfExists('app_workbook');
    }
}
