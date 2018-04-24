<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
    	'position'
    ];

    public static function scopeWithFixturesByDate($query, Carbon $game_date)
    {
        return $query->with([
                'fixtures' => function($q) use($game_date) {
                    $q->whereDate('game_date', $game_date);
                },
                'fixtures.homeTeam',
                'fixtures.awayTeam'
            ])
            ->get();
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
