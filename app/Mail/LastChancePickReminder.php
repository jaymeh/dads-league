<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LastChancePickReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
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
        $week = week_number();

        $token_link = route('picks.weekly', ['token' => $this->token]);

        $date = new Carbon('this saturday');

        return $this->markdown('emails.last-chance-pick-reminder')
            ->subject('Last chance to pick your team for Week ' . $week)
            ->with(compact('week', 'token_link'))
            ->with(['date' => $date->format('l dS F Y')]);
    }
}
