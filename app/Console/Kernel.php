<?php

namespace App\Console;

use App\Console\Commands\SendReport;
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
        Commands\CleanerCron::class,
        Commands\SendReport::class,
        Commands\DeleteInactivUser::class,
        // App\Console\Commands\SendReport::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('cleaner:cron')->everyDay()->at('04:00');
        // $schedule->command(AdWillExpire::class)->daily();
        // $schedule->command(ContractWillExpire::class)->daily();
        // // $schedule->command(SendReport::class)->everyTwoMinutes();
        // $schedule->command(TeteDeListe::class)->hourly();

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
