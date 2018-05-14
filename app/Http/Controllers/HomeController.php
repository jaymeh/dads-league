<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Table;
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
            $season = Season::whereYear('end_date', $current_year);
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

        return view('welcome', compact('season', 'league_table'));
    }
}
