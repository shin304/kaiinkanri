<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePschoolAddCheckZipCsv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pschool', function (Blueprint $table) {

            $table->tinyInteger('is_zip_csv')->default(1)->nullable()->after('valid_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pschool', function (Blueprint $table) {

            $table->dropColumn('is_zip_csv');
        });
    }
}
