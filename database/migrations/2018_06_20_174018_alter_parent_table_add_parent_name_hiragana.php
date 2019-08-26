<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParentTableAddParentNameHiragana extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent', function (Blueprint $table) {
            $table->string('parent_name_hiragana', 255)->nullable()->comment('請求先名前ひらがな')->after('parent_name');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent', function (Blueprint $table) {
            $table->dropColumn('parent_name_hiragana');
        });
    }
}
