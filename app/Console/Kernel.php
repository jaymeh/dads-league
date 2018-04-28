<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Get scores from last week.
        $schedule->command('cron:get-scores')
            ->sundays()->at('05:00');

        // Tally up all scores weekly.
        $schedule->command('cron:tally-scores')
            ->sundays()->at('10:00');

        // Grab all fixtures for this week.
        $schedule->command('cron:weekly-fixtures')
            ->sundays()->at('11:00');

        // TODO: Email sunday lunchtime with scores. Clarify if this is just one week list or all time.

        // Send reminder to everyone to send in their picks.
        $schedule->command('cron:send-weekly-picks')
            ->sundays()->at('12:00');

        // On Friday if someone hasn't picked send email prompting pick with Mark CC'd.
        
        // Prompt for new season input on 14th July each year.
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
