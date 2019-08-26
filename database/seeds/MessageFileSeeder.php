<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class MessageFileSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 画面ごとにメッセージを追加する時、seederファイルを作成して実行する
        $this->seed('EventMessageSeeder');
        $this->seed('ParentMessageSeeder');
        $this->seed('HomeMessageSeeder');
        $this->seed('MailMessageSeeder');
        $this->seed('PschoolMessageSeeder');
        $this->seed('StudentMessageSeeder');
        $this->seed('ProgramMessageSeeder');
        $this->seed('BulletinBoardSeeder');
        $this->seed('CoachMessageSeeder');
        $this->seed('LabelMessageSeeder');
        $this->seed('ClassMessageSeeder');
        $this->seed('BroadcastMailMessageSeeder');
        $this->seed('InvoiceMessageSeeder');
        $this->seed('MenuMessageSeeder');

    }
}
