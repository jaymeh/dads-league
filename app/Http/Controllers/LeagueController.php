<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
    	$season = current_season();

    	if(!$season)
    	{
    		$season = Season::whereYear('end_date', $current_year);
    	}
        
    	$league_table = Table::where('season_id', $season->id)
            ->with('player')
            ->orderByDesc('score')
            ->orderByDesc('wins')
            ->get()
            ->mapWithKeys(function($table) {
                return [$table->player->name => $table];
            });

    	return view('content.league.index', compact('season', 'league_table'));
    }
}
