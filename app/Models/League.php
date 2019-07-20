<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
    	'position'
    ];

    public static function scopeWithFixturesByDate($query, Carbon $game_date, $excluded_team_ids)
    {
        return $query->with([
                'fixtures' => function($q) use($game_date) {
                    $q->where('game_date', $game_date);
                },
                'fixtures.homeTeam',
                'fixtures.awayTeam'
            ])
            ->whereHas('fixtures', function($q) use($game_date) {
                $q->where('game_date', $game_date);
            })
            ->get()
            ->map(function($result) use ($excluded_team_ids) {
                $should_exclude = $result->fixtures->whereIn('home_team_id', $excluded_team_ids)->whereIn('away_team_id', $excluded_team_ids)->pluck('id');

                
                $result_fixtures = $result->fixtures->map(function($fixture) use ($should_exclude) {

                    if($should_exclude->contains($fixture->id))
                    {
                        $fixture->disabled = true;
                    }
                    else
                    {
                        $fixture->disabled = false;
                    }

                    return $fixture;
                });

                $result->fixtures = $result_fixtures;

                return $result;
            });
    }

    public function fixtures()
    {
    	return $this->hasMany(Fixture::class);
    }

    public function teams()
    {
    	return $this->hasMany(Team::class);
    }
}
