<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailRemainderContentToInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->string('deposit_reminder_title',255)->nullable()->after('deposit_invoice_type');
            $table->text('deposit_reminder_content')->nullable()->after('deposit_reminder_title');
            $table->text('deposit_reminder_footer')->nullable()->after('deposit_reminder_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_header', function (Blueprint $table) {
            $table->dropColumn('deposit_reminder_title');
            $table->dropColumn('deposit_reminder_content');
            $table->dropColumn('deposit_reminder_footer');
        });
    }
}
