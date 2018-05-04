<?php

namespace App\Models;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
    	'name',
    	'logo',
    	'league_id'
    ];

    public function getFormattedPickGameDateAttribute()
    {
        if($this->pivot)
        {
            $date = new Carbon($this->pivot->game_date);
            return $date->format('d/m/Y');
        }
    }

    public function getCarbonGameDateAttribute()
    {
        if($this->pivot)
        {
            $date = new Carbon($this->pivot->game_date);
            return $date;
        }

        return false;
    }

    public function league()
    {
    	return $this->belongsTo(League::class);
    }

    public function player()
    {
    	return $this->belongsToMany(Player::class);
    }
}
