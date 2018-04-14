<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    public function picks()
    {
    	return $this->belongsTo(PlayerTeam::class);
    }
}
