<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Game::class, function (Faker $faker, $attributes) {
	$excluded_fixtures = App\Models\Game::select('fixture_id')
		->get()
		->reject(function($fixture_id) {
			if(!$fixture_id)
			{
				return true;
			}

			return false;
		})
		->pluck('fixture_id');

	if(!isset($attributes['fixture_id']))
	{
		$fixture = factory(App\Models\Fixture::class)->create();
	}
	else
	{
		$fixture = App\Models\Fixture::whereId($attributes['fixture_id'])
			->whereNotIn('id', $excluded_fixtures)
			->first();
	}

	if(!$fixture)
	{
		throw new Exception('No valid fixture has been found.');
	}

	$league = $fixture->league_id;

    return [
        'league_id' => $league,
      	'fixture_id' => $fixture->id,
      	'home_team_id' => $fixture->home_team_id,
      	'away_team_id' => $fixture->away_team_id,
      	'home_team_score' => $faker->numberBetween(1, 4),
      	'away_team_score' => $faker->numberBetween(1, 4),
      	'game_date' => $fixture->game_date
    ];
});
