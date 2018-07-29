<?php

namespace App\Console\Commands;

use App\Models\Season;
use App\Models\{ League, Team };
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class GetTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:get-teams {seasonId=false}';

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
        $season_id = $this->argument('seasonId');
        $season = Season::whereId($season_id)->first();

        if(!$season)
        {
            $season = current_season();

            if(!$season)
            {
                $date = now();
                $season = Season::whereYear('start_date', $date->format('Y'))
                    ->whereYear('end_date', $date->modify('+1 year')->format('Y'))
                    ->first();
            }
        }

        $clean_teams = [];
        foreach($leagues as $league_id => $league)
        {
            $client = new Client();
            $crawler = $client->request('GET', "https://www.theguardian.com/football/$league/table");

            $logoCrawler = clone $crawler;
            $teams = $crawler->filter('span.team-name')->each(function($node) {
                return $node->text();
            });

            $logos = $logoCrawler->filter('img.team-crest')->extract(['src']);

            foreach($logos as $key => $logo)
            {
                $logos[$key] = str_replace('60', '120', $logo);
            }

            foreach($teams as $key => $team)
            {
                $team_name = trim(preg_replace('/\s\s+/', ' ', $team));

                $new_logo = $this->saveLogo($team_name, $logos[$key]);

                $clean_teams[] = [
                    'name' => $team_name,
                    'logo' => $new_logo,
                    'league_id' => $league_id,
                    'season_id' => $season->id
                ];
            }
        }

        foreach($clean_teams as $team)
        {
            Team::updateOrCreate([
                'name'      => $team['name'], 
                'season_id' => $season->id
            ], $team);
        }
    }

    private function saveLogo($name, $logo) {
        $image_contents = @file_get_contents($logo);

        if(!$image_contents) {
            return;
        }

        $logo_name = str_slug($name);
        $image_path = "/assets/img/teams/$logo_name.png";
        $save_path = public_path($image_path);
        $save = file_put_contents($save_path, $image_contents);

        $base_url = URL::to('/');

        return $base_url . $image_path;
    }
}
