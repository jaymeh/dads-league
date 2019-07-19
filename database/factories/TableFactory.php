<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Table::class, function (Faker $faker, $attributes) {
    return [
        'season_id' => isset($attributes['season_id']) ? $attributes['season_id'] : factory(App\Models\Season::class)->create(),
        'player_id' => isset($attributes['player_id']) ? $attributes['player_id'] : factory(App\Models\Player::class)->create(),
        'score' => $faker->numberBetween(0, 10),
        'wins' => $faker->numberBetween(0, 2),
        'draws' => $faker->numberBetween(0, 2),
        'losses' => $faker->numberBetween(0, 2),
    ];
});