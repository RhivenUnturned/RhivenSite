<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Bank::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
            ->everyThirtyMinutes()
            ->appendOutputTo(base_path() . '/test.txt');

        $schedule->command('Misc:videos')->everyThirtyMinutes();

        $schedule->command('Bank:balancehistory')->everyFiveMinutes();

        $schedule->command('Bank:creditdailypayment')->dailyAt('3:00');

        $schedule->command('Bank:dailycharge')->dailyAt('3:00');
        $schedule->command('Bank:weeklycharge')->weeklyOn(1, '18:00');

        $schedule->command('Bank:creditdelete')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
