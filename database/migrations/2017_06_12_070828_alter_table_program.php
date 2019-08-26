<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProgram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'program', function (Blueprint $table) {
            $table->string ( 'program_code', 10)->nullable()->comment('イベントコード');
            $table->tinyInteger('send_mail_flag')->comment('ON/OFF送信メール機能')->nullable()->default(1);
            $table->string('mail_subject', 255)->nullable()->comment('メールのsubject');
            $table->tinyInteger('fee_type')->comment('1:会員種別による料金設定, 2:回数による料金設定')->nullable();
            $table->timestamp('recruitment_start')->nullable()->comment('募集開始日時');
            $table->timestamp('recruitment_finish')->nullable()->comment('募集終了日時');
            $table->text ( 'program_location')->nullable()->comment('開催場所');
            $table->string ( 'contact_number', 20)->nullable()->comment('お問い合わせ先：電話番号');
            $table->string ( 'contact_email', 55)->nullable()->comment('お問い合わせ先：メール');
            $table->boolean('schedule_flag')->nullable()->default(0)->comment('メールスケジュール');
            $table->timestamp('schedule_date')->nullable()->comment('予約送信日時');
            $table->string('payment_method', 25)->nullable()->comment('1:現金・振込, 2:口座振替, 3:その他');
            $table->timestamp( 'payment_due_date' )->nullable()->comment('支払期限');
            $table->timestamp( 'mail_send_date' )->nullable()->comment('メール送信日時(１回目)');
            $table->integer('member_capacity')->nullable()->comment('会員定員');
            $table->integer('non_member_capacity')->nullable()->comment('非会員定員');
            $table->boolean('application_deadline')->nullable()->default(0)->comment('支払期限');
            $table->text ( 'remark')->nullable()->comment('備考');
            $table->string ( 'person_in_charge1', 100)->nullable()->comment('担当者１');
            $table->string ( 'person_in_charge2', 100)->nullable()->comment('担当者２');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program', function (Blueprint $table) {
            $table->dropColumn('program_code');
            $table->dropColumn('send_mail_flag');
            $table->dropColumn('mail_subject');
            $table->dropColumn('fee_type');
            $table->dropColumn('recruitment_start');
            $table->dropColumn('recruitment_finish');
            $table->dropColumn('program_location');
            $table->dropColumn('contact_number');
            $table->dropColumn('contact_email');
            $table->dropColumn('schedule_flag');
            $table->dropColumn('schedule_date');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_due_date');
            $table->dropColumn('mail_send_date');
            $table->dropColumn('member_capacity');
            $table->dropColumn('non_member_capacity');
            $table->dropColumn('application_deadline');
            $table->dropColumn('remark');
            $table->dropColumn('person_in_charge1');
            $table->dropColumn('person_in_charge2');
        });
    }
}
