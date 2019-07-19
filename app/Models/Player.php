<?php

namespace App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];

    public function picks()
    {
    	return $this->belongsToMany(Team::class, 'player_teams')->withPivot('game_date');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function token()
    {
        return $this->hasOne(PickToken::class);
    }

    public function table()
    {
        return $this->hasMany(Table::class, 'tables');
    }
}
