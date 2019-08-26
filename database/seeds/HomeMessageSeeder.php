<?php

use Illuminate\Database\Seeder;
use App\Message;

class HomeMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ===========================
        // message_file_id = 1 : jp
        // ===========================

    // thangqg : 2017-06-07
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_location_title',
                'message_value'     => '開催場所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_start_title',
                'message_value'     => '開催時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_end_title',
                'message_value'     => '開催終了',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'this_month_title',
                'message_value'     => '今月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    // end -- thangqg : 2017-06-07

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'system_log_title',
                'message_value'     => 'システムからのお知らせ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // ===========================
        // message_file_id = 2 : en
        // ===========================

    // thangqg : 2017-06-07
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_location_title',
                'message_value'     => '開催場所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_start_title',
                'message_value'     => '開催時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'event_end_title',
                'message_value'     => '開催終了',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'screen_value'      => 'ホーム',
                'message_key'       => 'this_month_title',
                'message_value'     => '今月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    // end -- thangqg : 2017-06-07

    // update thangqg : 2017-09-21
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'message_key'       => 'event_start_title',
            ])->first();
            $message->message_value='開催開始';
            $message->save();
        }catch(\Exception$e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'message_key'       => 'event_start_title',
            ])->first();
            $message->message_value='開催開始';
            $message->save();
        }catch(\Exception$e){

        }
    // update thangqg : 2017-09-21
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.home',
                'message_key'       => 'end_title',
            ])->first();
            $message->message_value='終了日';
            $message->save();
        }catch(\Exception$e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.home',
                'message_key'       => 'end_title',
            ])->first();
            $message->message_value='終了日';
            $message->save();
        }catch(\Exception$e){

        }
    }
}
