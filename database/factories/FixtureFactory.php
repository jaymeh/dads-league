<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Fixture::class, function (Faker $faker, $attributes) {
	$league = $faker->numberBetween(1, 4);

	if(isset($attribute['game_date']))
	{
		$date = new Carbon\Carbon($attribute['game_date']);
	}
	else
	{
		$date = new Carbon\Carbon('this saturday');
	}

	$excluded_team_ids = App\Models\Fixture::select('home_team_id', 'away_team_id')
		->where('game_date', $date)
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

	if(!isset($teams[0]) || !isset($teams[1]))
	{
		throw new Exception('No teams left to build a new fixture.');
	}

    return [
        'league_id' => $league,
        'home_team_id' => $teams->first(),
        'away_team_id' => $teams->last(),
        'game_date' => $date
    ];
});
