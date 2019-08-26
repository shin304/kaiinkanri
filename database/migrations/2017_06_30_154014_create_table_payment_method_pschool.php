<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethodPschool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_pschool', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pschool_id')->unsigned()->comment('施設（pschool）に参照する');
            $table->string('payment_method_code',64)->comment('支払方法コード');
            $table->string('payment_method_name',64)->comment('支払方法「口座振替、コンビニ決済…」');
            $table->integer('payment_agency_id')->unsigned()->comment('収納代行会社テーブルに参照する');
            $table->timestamps();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();

            $table->unique(['pschool_id','payment_method_code','payment_agency_id'],'pschool_method_agency_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_pschool');
    }
}
