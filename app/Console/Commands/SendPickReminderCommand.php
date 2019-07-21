<?php

namespace App\Console\Commands;

use App\Mail\PickReminder;
use App\Models\PickToken;
use App\Models\Player;
use App\Models\Season;
use Carbon\Carbon;
use App\Models\Fixture;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Application;

class SendPickReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:send-weekly-picks {season_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the weekly email prompting for a pick this week.';

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
        $season_id = $this->argument('season_id');

        if(!$season_id)
        {
            // Find current season
            $season = current_season(true);
        }
        else
        {
            $season = Season::whereId($season_id)->first();
        }

        if(!$season)
        {
            $this->info('No season is currently active.');
            return;
        }
        
        // Clean out last weeks token.
        PickToken::query()->truncate();

        // Check if there are any picks this week
        $fixture_date = new Carbon('this saturday');
        $fixture_count = Fixture::where('game_date', '=', $fixture_date)->count();

        // dd($fixture_count);

        if(!$fixture_count) {
            Mail::raw('There are no fixtures to pick from this week.', function ($message) {
                $message->to('jaymehsykes@gmail.com')
                    ->subject('No picks this week.');
            });
            return;
        }

        // Get all players
        $players = Player::where('disabled', 0)->get();

        foreach($players as $player)
        {
            $pick_token = new PickToken;
            $expiry = new Carbon('this saturday');
            $expiry->setTime(10, 0);

            $pick_token->player_id = $player->id;
            $pick_token->expiry = $expiry;

            $pick_token->save();

            $environment = \App::environment();
            if ($environment == 'local') {
                sleep(6);
            }

            // Send the email.
            Mail::to($player->email)
                ->bcc('jaymeh@jaymeh.co.uk')
                ->send(new PickReminder($pick_token->token, $player));
        }
    }
}
