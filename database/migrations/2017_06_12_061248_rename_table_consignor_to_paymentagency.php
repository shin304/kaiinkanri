<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableConsignorToPaymentagency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('consignor', 'payment_agency');
        Schema::table('payment_agency', function (Blueprint $table) {
            $table->renameColumn('consignor_code', 'agency_code');
            $table->renameColumn('consignor_name', 'agency_name');
            $table->string('payment_link',255)->nullable()->after('base_name')->comment('プロセス支払へのリンク');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('payment_agency', 'consignor');
        Schema::table('consignor', function (Blueprint $table) {
            $table->renameColumn('agency_code', 'consignor_code');
            $table->renameColumn('agency_name', 'consignor_name');
            $table->dropColumn('payment_link');
        });
    }
}
