<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Team;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;

class GetWeeklyFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:weekly-fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all the fixtures each week.';

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
        // Find current season
        $season = current_season(true);

        if(!$season)
        {
            $this->info('No season is currently active.');
            return;
        }
        
        $client = new Client();

        $fixture_date = new Carbon('this saturday');
        $fixture_year = $fixture_date->format('Y');
        $fixture_month = $fixture_date->format('M');
        $fixture_day = $fixture_date->format('d');

        // dd("https://www.theguardian.com/football/fixtures/more/$fixture_year/$fixture_month/$fixture_day");

        $crawler = $client->request('GET', "https://www.theguardian.com/football/fixtures/more/$fixture_year/$fixture_month/$fixture_day");

        $fixture_table_data = $crawler->filter('.football-matches__container')->filter('table');

        $leagues = League::get();
        $teams = Team::get();

        if(!$leagues->count())
        {
            $this->error('Couldn\'t find any leagues. Please seed the leagues and try again!');
            return;
        }

        if(!$teams->count())
        {
            $this->error('Couldn\'t find any teams. Please run the team command and try again!');
            return;
        }

        $game_data = $fixture_table_data->each(function($node) use ($teams)
        {
            $temp_node = clone($node);
            $league = trim(preg_replace('/\s\s+/', ' ', $temp_node->filter('caption')->text()));

            $league_data = explode("\n", $league);

            $meta_data = array();

            $meta_data['league'] = $league_data[0];
            $meta_data['date'] = $league_data[1];

            $data = $node->filter('.football-match__teams')->each(function($sub_node) { return trim($sub_node->text()); });

            $results = [];

            foreach($data as $team) 
            {
                $people = trim(preg_replace('/\s\s+/', ';', $team));
                $people_data = explode(';', $people);

                // dd($people_data[0]);
                $home_team = $teams->where('name', $people_data[0])->first();
                $away_team = $teams->where('name', $people_data[1])->first();

                if(!$home_team || !$away_team)
                {
                    continue;
                }

                $home_team_id = $home_team->id;
                $away_team_id = $away_team->id;

                $results[] = compact('home_team_id', 'away_team_id');
            }

            return array('data' => $meta_data, 'games' => $results);
        });

        // Get them for Saturday
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

            // dd($games);

            foreach($games['games'] as $game)
            {
                $insert_data = $game;

                $date_object = new Carbon($date);

                $insert_data['league_id'] = $league_id;
                $insert_date['game_date'] = $date_object->format('Y-m-d');

                // If game isn't on fixture date then it isn't available.
                if($date_object->format('Y-m-d') != $fixture_date->format('Y-m-d'))
                {
                    continue;
                }

                Fixture::updateOrCreate([
                    'home_team_id' => $insert_data['home_team_id'], 
                    'away_team_id' => $insert_data['away_team_id'], 
                    'game_date' => $insert_date['game_date']
                ], $insert_data);
            }
        }
    }
}
