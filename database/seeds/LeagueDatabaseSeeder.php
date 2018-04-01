<?php

use Illuminate\Database\Seeder;

class LeagueDatabaseSeeder extends Seeder
{
	protected $leagues = [
		'premierleague' => 'Premier League',
        'championship' => 'Championship',
        'leagueonefootball' => 'League One',
        'leaguetwofootball' => 'League Two'
	];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach($this->leagues as $slug => $league)
    	{
    		App\League::updateOrCreate(['slug' => $slug], ['name' => $league, 'slug' => $slug]);
    	}
    }
}
