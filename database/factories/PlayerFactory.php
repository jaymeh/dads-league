<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Player::class, function (Faker $faker, $attributes) {	
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'disabled' => isset($attributes['disabled']) ? $attributes['disabled'] : 0
    ];
});
