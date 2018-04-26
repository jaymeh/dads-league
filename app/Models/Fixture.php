<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    protected $fillable = [
    	'home_team_id',
    	'away_team_id',
    	'league_id',
    	'game_date'
    ];

    protected $dates = ['game_date'];

    public function league()
    {
    	return $this->belongsTo(League::class, 'league_id', 'id');
    }

    public function homeTeam()
    {
    	return $this->hasOne(Team::class, 'id', 'home_team_id');
    }

    public function awayTeam()
    {
    	return $this->hasOne(Team::class, 'id', 'away_team_id');
    }

    public function playerTeam()
    {
        return $this->hasMany(PlayerTeam::class);
    }

    public function game()
    {
        return $this->hasOne(Game::class);
    }
}
