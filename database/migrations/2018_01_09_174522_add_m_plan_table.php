<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_name',64);
            $table->integer('number_register_id')->comment('登録会員数');
            $table->integer('number_active_id')->comment('有効会員数');
            $table->integer('number_institution_id')->comment(' 施設数（階層化の場合）');
            $table->dateTime('validation_date')->nullable();
            $table->decimal('plan_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_plan');
    }
}
