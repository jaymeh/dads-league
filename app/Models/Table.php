<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
    	'player_id',
    	'season_id',
    	'score',
    	'wins',
    	'draws',
    	'losses'
    ];

    public function player()
    {
    	return $this->belongsTo(Player::class);
    }
}
