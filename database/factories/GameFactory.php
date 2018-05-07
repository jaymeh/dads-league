<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Game::class, function (Faker $faker) {
	$league = $faker->numberBetween(1, 4);

	$excluded_fixtures = App\Models\Game::select('fixture_id')
		->get()
		->pluck('fixture_id')
		->reject(function($fixture_id) {
			if(!$fixture_id)
			{
				return true;
			}

			return false;
		});

	$fixture = App\Models\Fixture::where('league_id', $league)
		->whereDate('game_date', '<=', now())
		->whereNotIn('id', $excluded_fixtures)
		->inRandomOrder()
		->get()
		->first();

	if(!$fixture)
	{
		throw new Exception('No fixture found.');
	}

    return [
        'league_id' => $league,
      	'fixture_id' => $fixture->id,
      	'home_team_id' => $fixture->home_team_id,
      	'away_team_id' => $fixture->away_team_id,
      	'home_team_score' => $faker->numberBetween(1, 6),
      	'away_team_score' => $faker->numberBetween(1, 6),
      	'game_date' => $fixture->game_date
    ];
});
