<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempSchoolInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_school_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mail_address',64)->comment('メールアドレス');
            $table->string('login_pw',120)->comment('パスワード');
            $table->string('company_name',255)->comment('組織名（会社名）');
            $table->string('customer_name',255)->comment('登録者名');
            $table->string('zip_code',8)->comment('郵便番号');
            $table->integer('pref_id')->comment('都道府県');
            $table->integer('city_id')->comment('市区町村名');
            $table->string('address',255)->comment('番地');
            $table->string('building',64)->comment('ビル名');
            $table->string('phone',64)->comment('電話番号');
            $table->string('fax',64)->comment('FAX');
            $table->string('home_page',255)->comment('ホームページ');
            $table->string('register_code',255)->comment('確認コード');
            $table->tinyInteger('status')->comment('1 : 登録したところ , 2 : メールを確認した , 3 : アドミン確認 , 4 : 出来ない');
            $table->tinyInteger('is_locked')->default(0)->comment('1 : ロックされた , 0 : ロックされていない');
            $table->integer('register_admin')->unsigned();
            $table->integer('update_admin')->unsigned();
            $table->dateTime('register_date')->nullable()->comment('受付日');
            $table->dateTime('update_date')->nullable();
            $table->dateTime('delete_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_school_info');
    }
}
