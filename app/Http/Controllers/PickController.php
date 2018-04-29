<?php

namespace App\Http\Controllers;

use App\Http\Requests\PickSaveRequest;
use App\Models\Team;
use App\Models\{Fixture, League, PickToken, Player, PlayerTeam};
use Carbon\Carbon;
use Illuminate\Http\Request;

class PickController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get fixtures for this week wherehas and with player teams
        $fixtures_by_player_teams = Fixture::whereHas('playerTeam')
            ->with('playerTeam.player', 'game')
            ->get()
            ->sortByDesc('game_date')
            ->groupBy(function($fixture) {
                return $fixture->game_date->timestamp > now()->timestamp ? 'This Week' : $fixture->game_date->format('d/m/Y');
            });

        // $fixtures_by_player_teams = $fixtures_by_player_teams->

        return view('content.picks.index')
            ->with(compact('season', 'fixtures_by_player_teams', 'this_week_selection'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PickSaveRequest $request)
    {
        // Verify token.
        $token_player = PickToken::whereDate('expiry', '>=', now())
            ->where('token', $request->player_token)
            ->first();

        if(!$token_player)
        {
            trigger_message('Couldn\'t verify this player. Please try again.');
            return back()->withInput();
        }

        $date = $request->game_date;

        // Get current season.
        $season = current_season();
        
        $pick = PlayerTeam::updateOrCreate([
            'player_id' => $token_player->player_id, 
            'game_date' => $date,
            'season_id' => $season->id
        ], [
            'fixture_id' => $request->fixture,
            'team_id' => $request->pick
        ]);

        $team_name = Team::whereId($request->pick)->first()->name;

        trigger_message("Successfully saved your pick of $team_name. Good Luck!", 'success');

        $token_player->expiry = now();
        $token_player->save();

        return redirect()->route('picks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Weekly pick
     */
    public function weeklyPick($token)
    {
        $now = now();

        $this_saturday = new Carbon('this saturday');
        $season = current_season();

        // Find with token.
        $player_token = PickToken::where('token', $token)
            ->whereDate('expiry', '>=', $now)
            ->with(['player.picks' => function($q) use ($this_saturday, $season) {
                $q->where('game_date', '!=', $this_saturday);
                $q->where('season_id', $season->id);
            }])
            ->first();

        if(!$player_token)
        {
            // Invalid token given.
            trigger_message('Could not find the token provided. Please contact Mark.', 'error');

            return redirect()->route('picks.index');
        }

        if(!$player_token->active)
        {
            // Invalid token given.
            trigger_message('You have already picked your team this week.', 'error');

            return redirect()->route('index');
        }

        $player = $player_token->player;

        $excluded_ids = PlayerTeam::teamsToExclude($player->id, $this_saturday, $season);

        $grouped_fixtures = League::withFixturesByDate($this_saturday, $excluded_ids);

        $fixtures = $grouped_fixtures->mapWithKeys(function($league) {
            return $league->fixtures->mapWithKeys(function($fixture) {
                return [$fixture->id => [$fixture->homeTeam->id, $fixture->awayTeam->id]];
            });
        });

        if(!$fixtures->count())
        {
            return view('content.picks.no-fixtures');
        }

        $all_teams_by_league = $grouped_fixtures->map(function($league) use ($this_saturday, $excluded_ids) {
            return $league->fixtures->map(function($teams) use($excluded_ids, $league) {
                $team_names = [$teams->homeTeam->id => [
                    'id' => $teams->homeTeam->id,
                    'name' => $teams->homeTeam->name,
                    'logo' => $teams->homeTeam->logo,
                    'league' => $teams->homeTeam->league_id,
                    'league_name' => $league->name,
                    'disabled' => $excluded_ids->contains($teams->homeTeam->id) ? true : false
                ], $teams->awayTeam->id => [
                    'id' => $teams->awayTeam->id,
                    'name' => $teams->awayTeam->name,
                    'logo' => $teams->awayTeam->logo,
                    'league' => $teams->awayTeam->league_id,
                    'league_name' => $league->name,
                    'disabled' => $excluded_ids->contains($teams->awayTeam->id) ? true : false
                ]];

                return $team_names;
            });
        });

        $all_teams = $all_teams_by_league->flatten(2);

        // Disable anything that someone else picked.

        $active_pick = PlayerTeam::select('team_id')
            ->whereDate('game_date', $this_saturday)
            ->where('player_id', $player->id)
            ->first();
            // ->pluck('team_id');

        // dd($active_pick);

        return view('content.picks.weekly')->with(compact('this_saturday', 'token', 'grouped_fixtures', 'player', 'all_teams', 'fixtures', 'active_pick'));

        // dd($player_token);
    }

    public function list()
    {
        $season = current_season();
        $season_id = $season->id;

        $players = Player::with(['picks' => function($q) use($season_id) {
                $q->where('season_id', $season_id);
                $q->orderByDesc('game_date');
            }])
            ->get()
            ->sortByDesc('name');

        $total = $players->first()->picks->count();

        $weeks = collect();
        for($i = 1; $i <= $total; $i++)
        {
            $weeks->prepend($i);
        }
        // dd($weeks);
        // $weeks = $weeks->reverse();

        return view('content.picks.list')
            ->with(compact('players', 'weeks'));
    }
}
