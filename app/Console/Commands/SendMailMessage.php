<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;

// use App\Model\BulletinBoardTable;

class SendMailMessage extends Command
{
    use \App\Common\Email;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail {pschool_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check on schedule & Send mail by School_id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ( $this->argument('pschool_id') ) {
            $pschool_id = $this->argument('pschool_id');
            // Log::info( "Mail_Batch: pschool_id:{$pschool_id} start time is " . \Carbon\Carbon::now() );
            $this->sendMailBySchedule( $this->argument('pschool_id') );
            // Log::info( "Mail_Batch: pschool_id:{$pschool_id} end time is " . \Carbon\Carbon::now() );
        }
    }
}
