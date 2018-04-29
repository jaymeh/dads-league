<?php

namespace App\Console\Commands;

use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Console\Command;

class WeeklyResultsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:weekly-results-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out an email with results from last week and the table.';

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
        $last_weeks_date = new Carbon('last saturday');
        // Last weeks picks.
        $last_weeks_picks = PlayerTeam::with('player', 'fixture.homeTeam', 'fixture.awayTeam', 'team')
            ->whereDate('game_date', $last_weeks_date)
            ->get()
            ->mapWithKeys(function($pick) {
                // Team picked
                $picked_team = '';

                // Fixture Home Team
                
                // Fixture Away Team
            });

        dd($last_weeks_picks);
        // Last weeks results.
        // Send to all players.
        // 
    }
}
