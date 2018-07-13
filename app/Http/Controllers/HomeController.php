<?php

namespace App\Http\Controllers;

use App\Models\PlayerTeam;
use App\Models\Season;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

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
            ->orderByDesc('game_date')
            ->get()
            ->groupBy(function($pick) {
                return $pick->game_date->format('Y-m-d');
            })
            ->first();

        if($player_teams) {
            $player_teams = $player_teams
                ->flatten(1);
        }

        $picks_game_date = new Carbon('this saturday');

        if($weekly_picks)
        {
            $picks_game_date = $weekly_picks[0]->game_date->format('dS F Y');
        }

        if(!$weekly_picks) {
            $date = $player_teams->first()->game_date;
        }

        $date = $date->format('dS F Y');

        return view('welcome', compact('season', 'league_table', 'weekly_picks', 'picks_game_date', 'date', 'player_teams'));
    }
}
