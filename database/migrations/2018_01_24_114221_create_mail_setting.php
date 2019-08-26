<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mail',120);
            $table->tinyInteger('type')->comment('1 : 承認 , 2 : お問い合わせ');
            $table->tinyInteger('active_flag');
            $table->dateTime('register_date')->nullable();
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
        Schema::dropIfExists('mail_setting');
    }
}
