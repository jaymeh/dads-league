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
        return $this->markdown('emails.weekly-results');
    }
}
