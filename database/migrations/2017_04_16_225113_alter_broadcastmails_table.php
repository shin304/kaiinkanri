<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AlterBroadcastmailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'broadcast_mail', function (Blueprint $table) { 
            
            $table->integer ( 'staff_flag' )->after ( 'teacher_flag' )->default ( null );
            $table->dateTime ( 'time_send' )->after ( 'staff_flag' )->default ( null );
        } );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'broadcast_mail', function (Blueprint $table) {
            $table->dropColumn ( 'staff_flag' );
            $table->dropColumn ( 'time_send' );
        } );
    }
}
