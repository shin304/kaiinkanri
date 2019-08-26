<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterConsignorTableUniqueConsignorCodeAndName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'consignor', function (Blueprint $table) {
            $table->string('consignor_code', 100)->unique()->change();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consignor', function (Blueprint $table) {
            $table->dropUnique('consignor_code');
        });
    }
}
