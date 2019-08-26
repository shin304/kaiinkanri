<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateConsignorTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'consignor', function (Blueprint $table) {
            $table->increments ( 'id' )->unsigned ();
            $table->string ( 'consignor_code', 10 )->nullable ();
            $table->string ( 'consignor_name', 255 );
            $table->string ( 'base_name', 255 );
            $table->integer ( 'withdrawal_day1', false, true )->default ( null );
            $table->integer ( 'withdrawal_day2', false, true )->default ( null );
            $table->integer ( 'withdrawal_day3', false, true )->default ( null );
            $table->integer ( 'withdrawal_day4', false, true )->default ( null );
            $table->integer ( 'withdrawal_day5', false, true )->default ( null );
            $table->dateTime ( 'register_date' );
            $table->dateTime ( 'update_date' )->default ( null );
            $table->dateTime ( 'delete_date' )->default ( null );
            $table->integer ( 'register_admin', false, true );
            $table->integer ( 'update_admin', false, true );
            // $table->timestamps();
        } );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists ( 'consignor' );
    }
}
