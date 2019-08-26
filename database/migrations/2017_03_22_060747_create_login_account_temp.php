<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLoginAccountTemp extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'login_account_temp', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->string ( 'login_id' );
            $table->string ( 'login_pw_base64' );
            $table->integer ( 'register_admin' );
            $table->timestamps();
        } );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists ( 'login_account_temp' );
    }
}
