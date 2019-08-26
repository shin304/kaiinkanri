<?php

use Illuminate\Database\Seeder;
use App\Message;

class ClassToPlanTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // replace all screen_value current text クラス => プラン
        try{
            Message::whereNull('delete_date')->update([
                'screen_value' => DB::raw('REPLACE(screen_value, "クラス", "プラン")')
            ]);
        }catch (Exception $e){
            dd($e->getMessage());
        }


        // replace all message_value current text クラス => プラン
        try{
            Message::whereNull('delete_date')->update([
                'message_value' => DB::raw('REPLACE(message_value, "クラス", "プラン")')
            ]);
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }
}
