<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendMailMessage;
use App\Model\PschoolTable;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [ Commands\SendMailMessage::class,Commands\CreateInvoiceBatch::class,Commands\CreateStudentLoginInfo::class ]
    //
    ;
    
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule            
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        // send mail by schedule batch
        $school_list = PschoolTable::getInstance()->getActiveList();
        foreach ($school_list as $key => $school) {
            $schedule->command("send:mail {$school['id']}")->everyFiveMinutes();
        }

        // generate invoice daily if pschool setting date batch match today
        $schedule->command("create:invoice")->daily();

    }
}
