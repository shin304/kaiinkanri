<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCoachAddAddressBuilding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach', function (Blueprint $table) {
            $table->string ( 'address1_building', 256)->nullable()->comment('ビル1')->after('address1_address');
            $table->string ( 'address2_building', 256)->nullable()->comment('ビル2')->after('address2_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach', function (Blueprint $table) {
            $table->dropColumn('address1_building');
            $table->dropColumn('address2_building');
        });
    }
}
