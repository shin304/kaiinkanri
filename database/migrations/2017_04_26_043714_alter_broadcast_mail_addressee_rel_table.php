<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBroadcastMailAddresseeRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcast_mail_addressee_rel', function (Blueprint $table) {
            $table->integer('update_admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('broadcast_mail_addressee_rel', function (Blueprint $table) {
            $table->dropColumn('update_admin');
        });
    }
}
