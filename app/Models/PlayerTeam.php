<?php

namespace App\Models;

use Carbon\Carbon;
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

	public static function scopeThisWeeksTeams($query, Carbon $date)
	{
		return $query->whereDate('game_date', $date)
            ->get()
            ->pluck('team_id');
	}

	public static function scopeTeamsByPlayer($query, $player_id, $season = null)
	{
		if(!$season)
		{
			$season = current_season();
		}

		return $query->where('player_id', $player_id)
            ->where('season_id', $season->id)
            ->get()
            ->pluck('team_id');
	}

	public static function scopeTeamsToExclude($query, $player_id, Carbon $game_date, $season = null)
	{
		// Get the other player picks for this week.
        $previous_picks = $query->thisWeeksTeams($game_date);

        // Get the picks for this player in the past.
        $previous_player_picks = $query->teamsByPlayer($player_id, $season);

        return $previous_picks->merge($previous_player_picks)->unique();
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
