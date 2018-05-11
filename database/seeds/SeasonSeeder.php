<?php

use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables
        App\Models\Season::query()->truncate();
        App\Models\Player::query()->truncate();
        App\Models\Fixture::query()->truncate();
        App\Models\Game::query()->truncate();
        App\Models\PlayerTeam::query()->truncate();
        App\Models\Table::query()->truncate();

    	// Add fixtures 20 weeks from now.
    	$future_date = new Carbon\Carbon('this saturday');
        $future_date->modify('-1 year');
    	$future_date = $future_date->modify('+20 weeks');

    	$now = new Carbon\Carbon('this saturday');
        $now->modify('-1 year');

    	// Seed a season
    	$season = factory(App\Models\Season::class)->create([
            'start_date' => $now,
            'end_date' => $future_date
        ]);

        // Seed players
        $player_names = [
            'Dean',
            'Steph',
            'Matthew',
            'Bob',
            'Matty',
            'Mark',
            'Paula',
            'Pat'
        ];

        $players = collect();

        foreach($player_names as $player_name)
        {
            $player = factory(App\Models\Player::class)->create([
                'name' => $player_name,
                'email' => strtolower($player_name) . '@example.org'
            ]);

            $players->push($player);
        }

    	while($now != $future_date)
    	{
            $week_fixtures = collect();

            for($i = 0; $i < 10; $i++)
            {
        		// Seed some fixtures
        		$fixture = factory(App\Models\Fixture::class)->create([
        			'game_date' => $now
        		]);

                $week_fixtures->push($fixture);

                $game = factory(App\Models\Game::class)->create([
                    'league_id' => $fixture->league_id,
                    'fixture_id' => $fixture->id,
                    'home_team_id' => $fixture->home_team_id,
                    'away_team_id' => $fixture->away_team_id,
                    'game_date' => $fixture->game_date,
                ]);
            }

            // Add new pick for every player.
            $players->each(function($player) use (&$week_fixtures, $season, $now) {
                $fixture = $week_fixtures->random();
                $fixture_key = $week_fixtures->keys()->random();
                $fixture = $week_fixtures[$fixture_key];

                $teams = collect([ $fixture->home_team_id, $fixture->away_team_id ]);

                $week_fixtures->forget($fixture_key);

                factory(App\Models\PlayerTeam::class)->create([
                    'player_id'     => $player->id,
                    'season_id'     => $season->id,
                    'team_id'       => $teams->random(),
                    'fixture_id'    => $fixture->id,
                    'game_date'     => $now
                ]);
            });
            
    		$now = $now->modify('+1 week');
    	}

        // Add fixtures for a week after.
        // $now = $now->modify('+1 week');

        for($i = 0; $i < 10; $i++)
        {
            factory(App\Models\Fixture::class)->create([
                'game_date' => $now
            ]);
        }

        // Run the command to build the table.
        Artisan::call('cron:tally-scores', [
            'season_id' => $season->id
        ]);
    }
}
