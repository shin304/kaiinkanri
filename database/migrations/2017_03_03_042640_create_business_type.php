<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('business_type', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id',10)->unique();
            $table->string('type_name',255)->nullable();
            $table->string('resource_file',25)->nullable();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
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
        Schema::dropIfExists('business_type');
    }
}
