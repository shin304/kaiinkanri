<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethodSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_method_id')->unsigned()->comment('支払方法テブールに参照する');
            $table->integer('payment_agency_id')->unsigned()->default(0)->comment('収納代行会社テーブルに参照する');
            $table->string('item_name',64)->unique()->comment('項目名（プログラムで使う）');
            $table->string('item_display_name',64)->unique()->comment('項目表示名（締切日、引き落とし日…）');
            $table->tinyInteger('item_type')->comment('項目種類（1:text, 2:select, 3:radio...）');
            $table->text('description')->nullable()->comment('説明');
            $table->text('note')->nullable()->comment('注意事項');
            $table->string('place_holder',64)->nullable()->comment('placeholderの説明テキスト');
            $table->text('default_value')->nullable()->comment('デフォルト値');
            $table->integer('sort_no')->nullable()->comment('表示順番');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();

            $table->unique(['payment_method_id','payment_agency_id','item_name'],"method_and_agency_is_unique");
        });
        DB::statement("ALTER TABLE `payment_method_setting` comment '支払方法に対して使う項目の設定テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_setting');
    }
}
