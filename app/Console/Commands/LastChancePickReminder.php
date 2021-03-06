<?php

namespace App\Console\Commands;

use App\Mail\LastChancePickReminder as LastChanceEmail;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LastChancePickReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:last-chance-pick-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out a pick reminder to anyone who hasn\'t picked yet.';

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
        $season = current_season(true);

        if(!$season)
        {
            $this->info('No season is currently active.');
            return;
        }

        // Find everyone who doesn't have a pick for this week.
        $players_not_picked = Player::whereDoesntHave('picks', function($q) {
                $q->where('game_date', new Carbon('this saturday'));
            })
            ->where('disabled', 0)
            ->with('token')
            ->get();

        // dd($players_not_picked);

        foreach($players_not_picked as $player)
        {
            // dd($player);
            if($player->token) {
                Mail::to($player->email)
                    ->cc('mark@shelleyfootball.club')
                    ->send(new LastChanceEmail($player->token->token));
            } else {
                Mail::raw('Couldn\'t find token for player with email of ' . $player->email . '.', function($message) {
                    $message->to('jaymeh@jaymeh.co.uk');
                    $message->subject('Couldn\'t find token for player!');
                });
            }
        }
    }
}
