<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class GetTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $leagues = [
            'premierleague',
            // 'championship',
            // 'leagueonefootball',
            // 'leaguetwofootball'
        ];

        foreach($leagues as $league)
        {
            $client = new Client();

            $crawler = $client->request('GET', "https://www.theguardian.com/football/$league/table");

            $teams = $crawler->filter('span.team-name')->each(function($node) {
                return $node->text();
            });

            foreach($teams as $team)
            {
                $team = trim(preg_replace('/\s\s+/', ' ', $team));

                
            }

            $teams = trim(preg_replace('/\s\s+/', ' ', $temp_node->filter('caption')->text()));

            dd($teams);
        }
        
    }
}
