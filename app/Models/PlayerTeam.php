<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PlayerTeam extends Model
{
	protected $fillable = [
		'season_id',
		'player_id',
		'fixture_id',
		'team_id',
		'game_date'
	];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'game_date'
    ];

    protected $game_status_values = [
    	'Win' => 3,
    	'Draw' => 1,
    	'Lose' => 0
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
		return $query
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
		if(!$season)
		{
			$season = current_season();
		}

		return $query->with('fixture')
			->where(function($q) use($game_date) {
				$q->whereDate('game_date', $game_date);
			})
			->orWhere(function($q) use($player_id, $season) {
				$q
					->where('player_id', $player_id)
            		->where('season_id', $season->id);
			})
			->get()
			->map(function($result) {
				return !$result->fixture ? $result->team_id : [$result->fixture->home_team_id, $result->fixture->away_team_id];
			})
			->flatten()
			->unique();
	}

	public function scopePlayerAlreadyPicked($query, $player_id, $team_id, $game_date)
	{
		return $query->where(function($q) use ($player_id, $team_id, $game_date) {
			// Check not picked in past by this player
			$q->where('player_id', $player_id)
				->where('team_id', $team_id)
				->whereDate('game_date', '!=', $game_date);
		});
	}

	public function scopeTeamPickedByOtherPlayer($query, $team_id, $player_id, $game_date)
	{
		return $query->where(function($q) use ($team_id, $player_id, $game_date) {
			// Check other person hasn't picked this week.
			$q->where('team_id', $team_id)
				->whereDate('game_date', $game_date)
				->where('player_id', '!=', $player_id);
		});
	}

	public function scopePickingRivalingTeam($query, $fixture_id, $game_date, $player_id)
	{
		return $query->orWhere(function($q) use ($fixture_id, $game_date, $player_id) {
			// Check not picking rivaling team.
			$q->where('fixture_id', $fixture_id)
				->whereDate('game_date', $game_date)
				->where('player_id', '!=', $player_id);
		});
	}

	public function getGameStatusAttribute()
	{
		if(!$this->fixture)
		{
			return false;
		}
		if(!$this->fixture->game)
		{
			return false;
		}

		if(!$game = $this->fixture->game)
		{
			return false;
		}

		$home_score = $game->home_team_score;
		$away_score = $game->away_team_score;

		if($home_score == $away_score)
		{
			return 'Draw';
		}

		$team_type = $game->home_team_id == $this->team_id ? 'Home' : 'Away';

		if($team_type == 'Home') 
		{
			return $home_score > $away_score ? 'Win' : 'Lose';
		}

		if($team_type == 'Away')
		{
			return $away_score > $home_score ? 'Win' : 'Lose';
		}

		return false;
	}

	public function getGameStatusClassAttribute()
	{
		$map = [
			'Win' => 'is-success',
			'Lose' => 'is-danger',
			'Draw' => 'is-warning'
		];

		$status = $this->gameStatus;

		if(!isset($map[$status]))
		{
			return '';
		}

		return $map[$status];
	}

	/**
	 * Assigns the win, lose and draw conditions to a number so they can 
	 * easily be sorted.
	 * @return [type] [description]
	 */
	public function getGameStatusSortAttribute() {
		$status = $this->gameStatus;

		if(isset($this->game_status_values[$status])) {
			return $this->game_status_values[$status];
		}

		return false;
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function fixture()
	{
		return $this->belongsTo(Fixture::class);
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
