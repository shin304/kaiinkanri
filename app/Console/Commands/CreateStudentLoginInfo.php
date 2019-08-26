<?php

namespace App\Console\Commands;

use App\Model\LoginAccountTable;
use Illuminate\Console\Command;
use DB;
use Log;

// use App\Model\BulletinBoardTable;

class CreateStudentLoginInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:student_login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create login info for all student';

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
        $loginTable = LoginAccountTable::getInstance();
        $loginTable->createListStudentAccount();
    }
}
