<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class GetScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:get-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs off and grabs recent scores';

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
        $client = new Client();

        $content = [];

        $crawler = $client->request('GET', 'https://www.theguardian.com/football/results/more/2018/Mar/25');

        // dd($crawler);

        $leagues = $crawler->filter('table')->filter('caption')->each(function($node) {
            return $node->text();
        });

        $teams = $crawler->filter('table')->extract(array('_text', 'class', 'href'));

        dd($leagues, $teams);

        

        dd($elements);

        $text = $crawler->text();

        dd($text);

        $file_data = file_put_contents(storage_path('downloads/page.html'), $text);

        if(!$file_data)
        {
            dd('cache failed');
        }

        dd('finished');
    }
}
