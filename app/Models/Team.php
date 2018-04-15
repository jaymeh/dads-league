<?php

namespace App\Models;

use App\Models\Player;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
    	'name',
    	'logo',
    	'league_id'
    ];

    public function league()
    {
    	return $this->belongsTo(League::class);
    }

    public function player()
    {
    	return $this->belongsToMany(Player::class);
    }
}
