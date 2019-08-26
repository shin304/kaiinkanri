<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMPlanCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_plan_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_type')->comment('1 : 登録会員数 , 2 : 有効会員数 , 3 : 施設数（階層化の場合）');
            $table->string('category_name',64);
            $table->integer('category_value')->nullable();
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
        Schema::dropIfExists('m_plan_category');
    }
}
