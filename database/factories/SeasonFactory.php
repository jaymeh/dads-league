<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Season::class, function (Faker $faker) {
	$this_year = date('Y');

	$next_year = intval($year) + 1;

    return [
    	'name' => $this_year . '/' . $next_year,
    	'start_date' => now(),
    	'end_date' => now()->modify('+38 weeks')
    ];
});
