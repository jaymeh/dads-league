<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Season;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $season = current_season();
        if(!$season)
        {
            $date = now();
            $season = Season::whereYear('start_date', $date->format('Y'))
                ->whereYear('end_date', $date->modify('+1 year')->format('Y'))
                ->first();
        }
        
        // TODO: Add this snippet where we get the league table to a helper function so that 
        // it can be used by both this function and the league controller
        $league_table = Table::where('season_id', $season->id)
            ->with('player')
            ->orderByDesc('score')
            ->orderByDesc('wins')
            ->get()
            ->mapWithKeys(function($table) {
                return [$table->player->name => $table];
            });

        if(!$league_table->count()) {
            // Grab Players and add each one into there with default values.
            $league_table = Player::orderBy('name')
                ->get()
                ->mapWithKeys(function($player) {
                    return [
                        $player->name => (object) [
                            'score' => 0,
                            'wins' => 0,
                            'losses' => 0,
                            'draws' => 0
                        ]
                    ];
                });
        }

        $date = new Carbon('last saturday');

        $weekly_picks = PlayerTeam::where('season_id', $season->id)
            ->with('team', 'player')
            ->whereDate('game_date', '>', now())
            ->orderByDesc('game_date')
            ->get()
            ->groupBy(function($pick) {
                return $pick->game_date->format('dS F Y');
            })
            ->first();

        if($weekly_picks)
        {
            $date = new Carbon($weekly_picks->first()->game_date);
            $date = $date->modify('-1 week');
        }

        $player_teams = PlayerTeam::where('season_id', $season->id)
            ->with('team', 'player', 'fixture.game')
            ->whereHas('fixture.game')
            ->orderByDesc('game_date')
            ->get()
            ->groupBy(function($pick) {
                return $pick->game_date->format('Y-m-d');
            })
            ->first();

        if($player_teams) {
            $player_teams = $player_teams
                ->sortByDesc('gameStatusSort')
                ->flatten(1);
        }

        $picks_game_date = new Carbon('this saturday');

        if($weekly_picks)
        {
            $picks_game_date = $weekly_picks[0]->game_date->format('dS F Y');
        }

        if(!$weekly_picks) {
            if($player_teams) {
                $date = $player_teams->first()->game_date;
            } else {
                $date = now();
            }
        }

        $date = $date->format('dS F Y');

        return view('welcome', compact('season', 'league_table', 'weekly_picks', 'picks_game_date', 'date', 'player_teams'));
    }
}
