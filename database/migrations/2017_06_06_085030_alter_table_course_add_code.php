<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCourseAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->string ( 'course_code', 10)->nullable()->comment('イベントコード');
            $table->string ( 'contact_number', 20)->nullable()->comment('お問い合わせ先：電話番号');
            $table->string ( 'contact_email', 55)->nullable()->comment('お問い合わせ先：メール');
            $table->text ( 'remark')->nullable()->comment('備考');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropColumn('course_code');
            $table->dropColumn('contact_number');
            $table->dropColumn('contact_email');
            $table->dropColumn('remark');
        });
    }
}
