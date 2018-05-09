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

    	while($now != $future_date)
    	{
    		// Seed some fixtures
    		$fixtures = factory(App\Models\Fixture::class, 10)->create([
    			'game_date' => $now
    		]);

    		$now = $now->modify('+1 week');
    	}
    }
}
