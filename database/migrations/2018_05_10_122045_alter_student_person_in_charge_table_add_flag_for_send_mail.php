<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudentPersonInChargeTableAddFlagForSendMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_person_in_charge', function (Blueprint $table) {
            $table->tinyInteger('check_send_mail_flag')->default(0)->after('person_email')->nullable()->comment('1:表示 0:なし');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_person_in_charge', function (Blueprint $table) {
            $table->dropColumn('check_send_mail_flag');
        });
    }
}
