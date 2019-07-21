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
        // Find current season
        $season = current_season(true);

        if(!$season || $season->start_date > now())
        {
            $this->info('No season is currently active.');
            return;
        }

        $last_week = new Carbon('last saturday');
        $results = PlayerTeam::whereHas('team')
                ->with(['player' => function($q) {
                    $q->where('disabled', 0);
                }, 'team', 'fixture.game'])
                ->where('game_date', $last_week)
                ->get();

        if(!$results->count()) {
            $this->info('No results this week.');
            return;
        }

        $season = current_season();

        $table = Table::where('season_id', $season->id)
            ->whereHas('player', function($q) {
                return $q->where('disabled', 0);
            })
            ->orderByDesc('score')
            ->get()
            ->mapWithKeys(function($table) {
                return [$table->player->name => $table];
            });

        // Get all active players emails.
        $players = Player::where('disabled', 0)->get()->pluck('email');

        if(!count($players)) {
            Mail::raw('There are no valid result entries this week.', function ($message) {
                $message->to('jaymehsykes@gmail.com')
                    ->subject('No results this week.');
            });
        }
        
        Mail::to($players)
            ->bcc('jaymeh@jaymeh.co.uk')
            ->send(new WeeklyResults($results, $table));
    }
}
