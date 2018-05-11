<?php

namespace App\Mail;

use App\Models\Player;
use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PickReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, Player $player)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = route('index');
        $token_link = route('picks.weekly', ['token' => $this->token]);

        $season = current_season();

        $next_game_date = next_game_date(true);

        $week = $next_game_date['week_count'];
        $date = new Carbon($next_game_date['game_date']);

        return $this->markdown('emails.pick-reminder')
            ->subject('Pick your teams for Week ' . $week)
            ->with([
                'url' => $url,
                'token_link' => $token_link,
                'week' => $week,
                'date' => $date->format('l dS F Y')
            ]);
    }
}
