<?php

namespace App\Console\Commands;

use App\Mail\PickReminder;
use App\Models\PickToken;
use App\Models\Player;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
            $season = current_season();
        }
        else
        {
            $season = Season::whereId($season_id)->first();
        }
        
        // Clean out last weeks token.
        PickToken::query()->truncate();

        // Get all players
        $players = Player::get();

        $picks = [];

        foreach($players as $player)
        {
            $pick_token = new PickToken;
            $expiry = new Carbon('this saturday');
            $expiry->setTime(10, 0);

            $pick_token->player_id = $player->id;
            $pick_token->expiry = $expiry;

            $pick_token->save();

            // Send the email.
            Mail::to($player->email)->send(new PickReminder($pick_token->token, $player));
        }
    }
}
