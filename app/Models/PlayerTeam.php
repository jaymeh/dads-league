<?php

namespace App;

use App\Models\Week;
use Illuminate\Database\Eloquent\Model;

class PlayerTeam extends Model
{
    public function week()
    {
    	return $this->hasOne(Week::class);
    }
}
