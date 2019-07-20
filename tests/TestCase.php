<?php

namespace Tests;

use Illuminate\Foundation\Testing\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Season;
use App\Models\Team;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;
    use CreatesApplication;

    public function setUp() {
        parent::setUp();

        // Create a new season to use.
        $season = factory(Season::class)->create();

        \Artisan::call("db:seed", ['--class' => 'LeagueTableSeeder']);

        $team1 = new Team([
            'league_id' => '1',
            'season_id' => $season->id,
            'name' => 'AFC Bournemouth',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/afc-bournemouth.png' 
        ]);
        $team1->save();

        $team2 = new Team([
            'league_id' => '1',
            'season_id' => $season->id,
            'name' => 'Aston Villa',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/aston-villa.png' 
        ]);
        $team2->save();

        $team3 = new Team([
            'league_id' => '2',
            'season_id' => $season->id,
            'name' => 'Barnsley',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/barnsley.png' 
        ]);
        $team3->save();

        $team4 = new Team([
            'league_id' => '2',
            'season_id' => $season->id,
            'name' => 'Bury',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/bury.png' 
        ]);
        $team4->save();

        $team5 = new Team([
            'league_id' => '3',
            'season_id' => $season->id,
            'name' => 'Coventry',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/coventry.png' 
        ]);
        $team5->save();

        $team6 = new Team([
            'league_id' => '3',
            'season_id' => $season->id,
            'name' => 'Crawley',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/crawley.png' 
        ]);
        $team6->save();

        $team7 = new Team([
            'league_id' => '4',
            'season_id' => $season->id,
            'name' => 'Crewe',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/crewe.png' 
        ]);
        $team7->save();

        $team8 = new Team([
            'league_id' => '4',
            'season_id' => $season->id,
            'name' => 'Derby',
            'logo' => 'https://shelleyfootball.club/assets/img/teams/derby.png' 
        ]);
        $team8->save();
    }
}
