<?php

use App\Models\League;
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
        League::truncate();
        
        $position = 0;

        $path = '/assets/img/leagues';

    	foreach($this->leagues as $slug => $league)
    	{
            League::updateOrCreate(['slug' => $slug], 
            [
                'name' => $league, 
                'slug' => $slug, 
                'logo' => "$path/$slug.png",
                'position' => $position
            ]);

           $position++;
    	}
    }
}
