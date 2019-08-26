<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEntryChangeColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->integer('entry_type')->nullable()->comment('1:面談管理, 2:イベント, 3:プログラム')->change();
            $table->renameColumn('event_id', 'relative_id');
            $table->renameColumn('event_date', 'relative_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry', function (Blueprint $table) {
            $table->dropColumn('entry_type');
            $table->dropColumn('event_id');
            $table->dropColumn('event_date');
        });
    }
}
