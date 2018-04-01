<?php

namespace App\Console\Commands;

use App\League;
use App\Team;
use Goutte\Client;
use Illuminate\Console\Command;

class GetTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:get-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the list of teams in each league.';

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
        $leagues = League::get()->pluck('slug', 'id');

        $clean_teams = [];
        foreach($leagues as $league_id => $league)
        {
            $client = new Client();
            $crawler = $client->request('GET', "https://www.theguardian.com/football/$league/table");

            $logoCrawler = clone $crawler;
            $teams = $crawler->filter('span.team-name')->each(function($node) {
                return $node->text();
            });

            $logos = $logoCrawler->filter('span.team-crest')->extract(['style']);
            foreach($logos as $key => $logo)
            {
                preg_match_all('/\((.*?)\)/', $logo, $matches);

                if(isset($matches[1]))
                {
                    $logos[$key] = str_replace('60', '120', $matches[1][0]);
                }
            }

            foreach($teams as $key => $team)
            {
                $team_name = trim(preg_replace('/\s\s+/', ' ', $team));

                $clean_teams[] = [
                    'name' => $team_name,
                    'logo' => isset($logos[$key]) ? $logos[$key] : null,
                    'league' => $league_id
                ];
            }
        }

        foreach($clean_teams as $team)
        {
            Team::updateOrCreate(['name' => $team['name']], $team);
        }
    }
}
