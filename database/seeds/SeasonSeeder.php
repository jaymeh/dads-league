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
    	// Add fixtures 20 weeks from now.
    	$future_date = new Carbon\Carbon('this saturday');
    	$future_date = $future_date->modify('+20 weeks');

    	$now = new Carbon\Carbon('this saturday');

    	// Seed a season
    	$season = factory(App\Models\Season::class, 1);

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

        foreach($player_names as $player_name)
        {
            factory(App\Models\Player::class)->create([
                'name' => $player_name,
                'email' => strtolower($player_name) . '@example.org'
            ]);
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
            // Screw validation at this stage and see what happens.
            
    		$now = $now->modify('+1 week');
    	}

        // Run the command to build the table.
    }
}
