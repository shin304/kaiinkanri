<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentPersonInCharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'student_person_in_charge', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->integer('student_id')->unsigned()->comment('法人ID');
            $table->string('person_name', 255)->nullable()->comment('担当者名');
            $table->string('person_name_kana', 255)->nullable()->comment('担当者名フリガナ');
            $table->string('person_position', 64)->nullable()->comment('担当者役職');
            $table->string('person_office_name', 255)->nullable()->comment('部署名');
            $table->string('person_office_tel', 15)->nullable()->comment('部署TEL');
            $table->string('person_email', 255)->nullable()->comment('担当者EMAIL');
            $table->timestamps ();
            $table->integer('register_admin')->unsigned()->nullable();
            $table->integer('update_admin')->unsigned()->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists ( 'student_person_in_charge' );
    }
}
