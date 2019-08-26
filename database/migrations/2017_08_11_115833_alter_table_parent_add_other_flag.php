<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableParentAddOtherFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent', function (Blueprint $table) {
            $table->boolean( 'other_name_flag')->nullable()->default(0)->comment('請求先宛名：０：請求先名、１：その他');
            $table->boolean( 'other_address_flag')->nullable()->default(0)->comment('請求先住所：０：請求先登録住所、１：その他');
            $table->string('parent_name_other', 255)->nullable()->comment('請求先名の他');
            $table->integer('pref_id_other')->unsigned()->nullable()->comment('都道府県の他');
            $table->integer('city_id_other')->unsigned()->nullable()->comment('市区町村の他');
            $table->string('zip_code1_other', 3)->nullable()->comment('郵便番号１の他');
            $table->string('zip_code2_other', 4)->nullable()->comment('郵便番号２の他');
            $table->string('address_other', 255)->nullable()->comment('番地の他');
            $table->string('building_other', 255)->nullable()->comment('ビル名の他');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent', function (Blueprint $table) {
            $table->dropColumn('other_name_flag');
            $table->dropColumn('other_address_flag');
            $table->dropColumn('parent_name_other');
            $table->dropColumn('pref_id_other');
            $table->dropColumn('city_id_other');
            $table->dropColumn('zip_code1_other');
            $table->dropColumn('zip_code2_other');
            $table->dropColumn('address_other');
            $table->dropColumn('building_other');
        });
    }
}
