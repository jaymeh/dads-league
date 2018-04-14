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

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
