<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PlayerTeam::class, function (Faker $faker, $attributes) {
	$season = current_season();

	if(!isset($attributes['game_date']))
	{
		$game_date = new Carbon\Carbon('this saturday');
	}
	else
	{
		$game_date = new Carbon\Carbon($attributes['game_date']);
	}

	$player_query = App\Models\PlayerTeam::whereDate('game_date', $game_date)
		->get();

	$excluded_player_ids = $player_query->pluck('player_id');
	$excluded_fixture_ids = $player_query->pluck('fixture_id');

	if(!$attributes['player_id']) 
	{
		// Get a random player, that hasn't picked yet.
		$player = App\Models\Player::inRandomOrder()
			->whereNotIn('id', $excluded_player_ids)
			->get()
			->first();

		if(!$player)
		{
			throw new Exception('No player found.');
		}
	} else {
		$player = App\Models\Player::whereId($attributes['player_id'])->first();

		if(!$player) {
			throw new Exception('No player found.');
		}
	}

	$excluded_team_ids = $player_query->where('player_id', $player->id)
		->pluck('team_id');

	if(!$attributes['fixture_id'] || !$attributes['team_id']) {
		$fixture_with_team = App\Models\Fixture::select('id', 'home_team_id', 'away_team_id')
			->where(function($q) use ($excluded_team_ids, $game_date, $excluded_fixture_ids) {
				$q->whereNotIn('home_team_id', $excluded_team_ids);
				$q->whereNotIn('id', $excluded_fixture_ids);
				$q->whereDate('game_date', $game_date);
			})
			->orWhere(function($q) use ($excluded_team_ids, $game_date, $excluded_fixture_ids) {
				$q->whereNotIn('away_team_id', $excluded_team_ids);
				$q->whereNotIn('id', $excluded_fixture_ids);
				$q->whereDate('game_date', $game_date);
			})
			->get()
			->dd()
			->map(function($fixture) {
				$ids = collect([$fixture->home_team_id, $fixture->away_team_id]);
				$team_id = $ids->random(1)->first();

				return ['team_id' => $team_id, 'id' => $fixture->id];
			})
			->random(1)
			->first();

		if(!$fixture_with_team)
		{
			throw new Exception('No fixture found.');
		}
	} else {
		$fixture_with_team['id'] = $attributes['fixture_id'];
		$fixture_with_team['team_id'] = $attributes['team_id'];
	}

    return [
        'game_date' => $game_date,
        'season_id' => $season->id,
        'fixture_id' => $fixture_with_team['id'],
        'team_id' => $fixture_with_team['team_id'],
        'player_id' => $player->id,
    ];
});
