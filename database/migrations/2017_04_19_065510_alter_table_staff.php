<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'staff', function (Blueprint $table) {
            $table->string( 'staff_email',100 )->nullable ();
            $table->string( 'phone_no',15 )->nullable ();
            $table->string( 'address',255 )->nullable ();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'staff', function (Blueprint $table) {
            $table->dropColumn ( 'staff_email' );
            $table->dropColumn ( 'phone_no' );
            $table->dropColumn ( 'address' );
        } );
    }
}
