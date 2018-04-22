<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
    	'position'
    ];

    public function fixtures()
    {
    	return $this->hasMany(Fixture::class);
    }

    public function teams()
    {
    	return $this->hasMany(Team::class);
    }
}
