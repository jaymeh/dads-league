<?php

namespace App\Console\Commands;

use App\Mail\WeeklyResults;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
        $last_week = new Carbon('last saturday');
        $results = PlayerTeam::whereHas('team')
                ->with('player', 'team', 'fixture.game')
                ->where('game_date', $last_week)
                ->get();

        $season = current_season();

        $table = Table::where('season_id', $season->id)
            ->with('player')
            ->orderByDesc('score')
            ->get()
            ->mapWithKeys(function($table) {
                return [$table->player->name => $table];
            });

        // Get all active players emails.
        $players = Player::all()->pluck('email');

        // $to = $players->implode(',');

        Mail::to($players)
            ->send(new WeeklyResults($results, $table));
    }
}
