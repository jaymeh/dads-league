<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeeklyResults extends Mailable
{
    use Queueable, SerializesModels;
    public $results;
    public $table;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($results, $table)
    {
        $this->results = $results;
        $this->table = $table;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Weekly Results for ' . $this->results->first()->game_date->format('l dS F Y'))
            ->markdown('emails.weekly-results');
    }
}
