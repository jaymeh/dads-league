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

	public function getGameStatusAttribute()
	{
		// dd('gothere');
		if($this->fixture->game)
		{
			$game = $this->fixture->game;

			if(!$game)
			{
				return false;
			}

			$status = '';
			$home_score = $game->home_team_score;
			$away_score = $game->away_team_score;

			if($home_score == $away_score)
			{
				$status = 'Draw';
			}
			elseif($home_score < $away_score && $this->team_id == $game->home_team_id)
			{
				$status = 'Lose';
			}
			elseif($home_score > $away_score && $this->team_id == $game->home_team_id)
			{
				$status = 'Win';
			}
			elseif($home_score > $away_score && $this->team_id == $game->away_team_id)
			{
				$status = 'Lose';
			}
			elseif($home_score < $away_score && $this->team_id == $game->away_team_id)
			{
				$status = 'Win';
			}

			return $status;
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

	private function getGameStatusClass($status)
	{
		$map = [
			'Win' => 'is-success',
			'Lose' => 'is-danger',
			'Draw' => 'is-warning'
		];

		if(!isset($map[$status]))
		{
			return '';
		}

		return $map[$status];
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
