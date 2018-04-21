<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerTeam extends Model
{
	protected $fillable = [
		'player_id',
		'game_date',
		'team_id',
		'season_id'
	];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'game_date'
    ];

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'game_date';
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function team()
	{
		return $this->belongsTo(Team::class);
	}

	public function player()
	{
		return $this->belongsTo(Player::class);
	}
}
