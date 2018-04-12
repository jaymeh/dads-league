<?php

namespace App\Console\Commands;

use App\Models\{ Game, League, Team };
use Carbon\Carbon;
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

        $match_day = new Carbon('2017-08-08');

        $today = Carbon::now();
        $today->setTime(0, 0, 0);

        $days_left = $match_day->diff($today)->days;

        $bar = $this->output->createProgressBar($days_left);

        while($match_day != $today)
        {
            $year = $match_day->year;
            $month = $match_day->format('M');
            $day = $match_day->format('d');

            $crawler = $client->request('GET', "https://www.theguardian.com/football/results/more/$year/$month/$day");

            $table_data = $crawler->filter('.football-matches__container')->filter('table');

            $leagues = League::get();
            $teams = Team::get();

            if(!$leagues->count())
            {
                $this->error('Couldn\'t find any leagues. Please seed the leagues and try again!');
                break;
            }

            if(!$teams->count())
            {
                $this->error('Couldn\'t find any teams. Please run the team command and try again!');
                break;
            }

            $game_data = $table_data->each(function($node) use ($teams)
            {
                $temp_node = clone($node);
                $league = trim(preg_replace('/\s\s+/', ' ', $temp_node->filter('caption')->text()));

                $league_data = explode("\n", $league);

                $new_league = array();

                $new_league['league'] = $league_data[0];
                $new_league['date'] = $league_data[1];

                $data = $node->filter('.football-match--result')->each(function($sub_node) { return trim($sub_node->text()); });

                $results = [];

                foreach($data as $score) 
                {
                    $people = trim(preg_replace('/\s\s+/', ';', $score));
                    $people_data = explode(';', $people);

                    $home_team = $teams->where('name', $people_data[1])->first();
                    $away_team = $teams->where('name', $people_data[3])->first();

                    if(!$home_team || !$away_team)
                    {
                        continue;
                    }

                    $home_team_id = $home_team->id;
                    $home_team_score = intval($people_data[2]);
                    $away_team_id = $away_team->id;
                    $away_team_score = intval($people_data[4]);

                    $results[] = compact('home_team_id', 'home_team_score', 'away_team_id', 'away_team_score');
                }

                return array('data' => $new_league, 'games' => $results);
            });

            foreach($game_data as $games)
            {
                $date = $games['data']['date'];
                $league = $games['data']['league'];

                $league = $leagues->where('name', $league)->first();

                if(!$league)
                {
                    continue;
                }

                $league_id = $league->id;

                foreach($games['games'] as $game)
                {
                    $insert_data = $game;

                    $date_object = new Carbon($date);

                    $insert_data['league_id'] = $league_id;
                    $insert_date['game_date'] = $date_object->format('Y-m-d');

                    Game::updateOrCreate(['home_team_id' => $insert_data['home_team_id'], 'away_team_id' => $insert_data['away_team_id'], 'game_date' => $insert_date['game_date']], $insert_data);
                }
            }

            $match_day->addDay();
            $bar->advance();
        }

        $bar->finish();
    }
}
