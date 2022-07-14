<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    //MinuteUpdate
    protected $commands = [
        Commands\MinuteUpdate::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('minute:update')->everyMinute(); //->weekly();
    }
           
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

// ->weekly();->everyMinute()
// ->hourly();

