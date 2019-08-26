<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSchoolMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_menu', function (Blueprint $table) {
            $table->bigInteger('master_menu_id')->unsigned();
            $table->tinyInteger('viewable')->comment('表示可能０：出来ない、1：出来る');
            $table->tinyInteger('editable')->comment('編集可能０：出来ない、1：出来る');
            $table->string('icon_url', 255)->nullable();
            // modify comlumn nullable
            $table->string('default_menu_id')->nullable()->change();
            $table->string('menu_name')->nullable()->change();
            $table->string('menu_name_2')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_menu', function (Blueprint $table) {
            $table->dropColumn('master_menu_id');
            $table->dropColumn('viewable');
            $table->dropColumn('editable');
            $table->dropColumn('icon_url');
        });
    }
}
