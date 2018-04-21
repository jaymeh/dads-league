<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function picks()
  	{
  		return $this->hasMany(PlayerTeam::class);
    }
}
