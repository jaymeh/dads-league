<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Fixture::class, function (Faker $faker) {
	$league = $faker->numberBetween(1, 4);

	$this_saturday = new Carbon\Carbon('this saturday');

	$excluded_team_ids = App\Models\Fixture::select('home_team_id', 'away_team_id')
		->where('game_date', $this_saturday)
		->get()
		->map(function($fixture) {
			return [$fixture->home_team_id, $fixture->away_team_id];
		})
		->flatten()
		->sort()
		->toArray();

	$teams = App\Models\Team::where('league_id', $league)
		->whereNotIn('id', $excluded_team_ids)
		->inRandomOrder()
		->get()
		->pluck('id');

	var_dump($excluded_team_ids, $teams);
	if(!isset($teams[0]) || !isset($teams[1]))
	{
		throw new Exception('No teams left to build a new fixture.');
	}

    return [
        'league_id' => $league,
        'home_team_id' => $teams->first(),
        'away_team_id' => $teams->last(),
        'game_date' => $this_saturday
    ];
});
