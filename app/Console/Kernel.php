<?php

namespace App\Console;

use App\Jobs\MatchCandidatesToJobs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected $commands = [
    //     \App\Console\Commands\ArchiveOldJobs::class,
    //     \App\Console\Commands\RemoveUnverifiedUsers::class,
    //     \App\Console\Commands\RunJobMatcher::class,
    // ];

    protected function schedule(Schedule $schedule): void
    {
        info('sss');
        // $schedule->job(new MatchCandidatesToJobs)->hourly(); // or daily()
        $schedule->command('jobs:archive-old')->daily();
        $schedule->command('users:remove-unverified')->weekly();
        $schedule->command('jobs:match-candidates')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
}
