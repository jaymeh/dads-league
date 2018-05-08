<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PickToken::class, function (Faker $faker) {
	$player = App\Models\Player::inRandomOrder()->first();

	$expiry = new Carbon\Carbon('this saturday');
    $expiry->setTime(10, 0);

    return [
    	'expiry' => $expiry,
    	'player_id' => $player->id,
    	'active' => 1,
    ];
});
