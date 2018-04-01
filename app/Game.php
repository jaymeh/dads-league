<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
    	'home_team_id',
    	'home_team_score',
    	'away_team_id',
    	'away_team_score',
    	'league_id',
    	'game_date'
    ];
}
