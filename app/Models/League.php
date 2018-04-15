<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
    	'position'
    ];

    public function availableTeams()
    {
    	return $this->hasMany(AvailableTeam::class);
    }

    public function teams()
    {
    	return $this->hasMany(Team::class);
    }
}
