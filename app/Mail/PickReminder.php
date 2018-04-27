<?php

namespace App\Mail;

use App\Models\Player;
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
        $week_object = new Carbon('this saturday');
        return $this->markdown('emails.pick-reminder')
            ->with([
                'url' => $url,
                'token_link' => $token_link,
                'week' => $week_object->format('d/m/Y')
            ]);
    }
}
