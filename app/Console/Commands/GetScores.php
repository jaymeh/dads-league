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
    protected $description = 'Runs off and grabs recent scores. This will probably change next season but I can deal with it then.';

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

        $table_data = $crawler->filter('.football-matches__container')->filter('table');

        $game_data = $table_data->each(function($node)
        {
            $temp_node = clone($node);
            $league = trim(preg_replace('/\s\s+/', ' ', $temp_node->filter('caption')->text()));

            $league_data = explode("\n", $league);

            $new_league = array();

            $new_league['league'] = $league_data[0];
            $new_league['date'] = $league_data[1];

            $data = $node->filter('.football-match--result')->each(function($sub_node) { return $sub_node->text(); });

            $results = [];

            foreach($data as $score) 
            {
                $people = trim(preg_replace('/\s\s+/', ' ', $score));

                $people_data = explode(' ', $people);

                $match_status = $people_data[0];
                $team_1 = $people_data[1];
                $team_1_score = $people_data[2];
                $team_2 = $people_data[3];
                $team_2_score = $people_data[4];

                $results[] = compact('match_status', 'team_1', 'team_1_score', 'team_2', 'team_2_score');
            }

            return array('data' => $new_league, 'games' => $results);
        });
    }
}
