<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Season;
use App\Models\Table;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
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

    	return view('content.league.index', compact('season', 'league_table'));
    }
}
