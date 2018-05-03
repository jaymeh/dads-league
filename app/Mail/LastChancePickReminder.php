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
        $week = new Carbon('this saturday');
        $week = $week->format('d/m/Y');

        $token_link = route('picks.weekly', ['token' => $this->token]);

        return $this->markdown('emails.last-chance-pick-reminder')
            ->subject('Last chance to pick your team for ' . $week)
            ->with(compact('week', 'token_link'));
    }
}
