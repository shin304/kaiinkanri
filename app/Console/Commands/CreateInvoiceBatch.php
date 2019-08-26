<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;

// use App\Model\BulletinBoardTable;

class CreateInvoiceBatch extends Command
{
    use \App\Common\Invoice;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create invoice list if today is batch run date setting in school';

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
            echo("\n Run in batch file");
            $this->generateInvoiceDaily();
    }
}
