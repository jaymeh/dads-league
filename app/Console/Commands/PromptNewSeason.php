<?php

namespace App\Console\Commands;

use App\Mail\NewSeasonPromptMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PromptNewSeason extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:prompt-new-season';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prompts a new season with dates to be added to the db when it has been announced.';

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
        Mail::to('jaymeh@jaymeh.co.uk')
            ->send(new NewSeasonPromptMail());
    }
}
