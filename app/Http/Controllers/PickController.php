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
        $first_week = new Carbon('2017-08-12');
        
        // Get fixtures for this week wherehas and with player teams
        $player_team_by_date = PlayerTeam::with('player', 'fixture.game', 'fixture.homeTeam', 'fixture.awayTeam')
            ->get()
            ->sortByDesc('game_date')
            ->groupBy(function($player_team) {
                return $player_team->game_date->timestamp > now()->timestamp ? 'This Week' : $player_team->game_date->format('d/m/Y');
            });

        return view('content.picks.index')
            ->with(compact('season', 'player_team_by_date', 'this_week_selection'));
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

        $next_fixture_date = next_game_date();

        $fixture_date = new Carbon($next_fixture_date);
        $season = current_season();

        // Find with token.
        $player_token = PickToken::where('token', $token)
            ->whereDate('expiry', '>=', $now)
            ->with(['player.picks' => function($q) use ($fixture_date, $season) {
                $q->where('game_date', '!=', $fixture_date);
                $q->where('player_teams.season_id', $season->id);
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

        $excluded_ids = PlayerTeam::teamsToExclude($player->id, $fixture_date, $season);

        $grouped_fixtures = League::withFixturesByDate($fixture_date, $excluded_ids)
            ->map(function($league) use ($excluded_ids) {
                $fixture_map = collect();

                if($league->fixtures)
                {
                    $league->fixtures = $league->fixtures->map(function($fixture) use ($excluded_ids) {
                        $fixture->homeTeam->disabled = false;
                        if($excluded_ids->contains($fixture->homeTeam->id))
                        {
                            $fixture->homeTeam->disabled = true;
                        }

                        $fixture->awayTeam->disabled = false;
                        if($excluded_ids->contains($fixture->awayTeam->id))
                        {
                            $fixture->awayTeam->disabled = true;
                        }

                        return $fixture;
                    });
                }

                return $league;
            });

        $fixtures = $grouped_fixtures->mapWithKeys(function($league) {
            return $league->fixtures->mapWithKeys(function($fixture) {
                return [$fixture->id => [$fixture->homeTeam->id, $fixture->awayTeam->id]];
            });
        });

        if(!$fixtures->count())
        {
            return view('content.picks.no-fixtures');
        }

        $all_teams_by_league = $grouped_fixtures->map(function($league) use ($fixture_date, $excluded_ids) {
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
            ->whereDate('game_date', $fixture_date)
            ->where('player_id', $player->id)
            ->first();

        return view('content.picks.weekly')->with(compact('fixture_date', 'token', 'grouped_fixtures', 'player', 'all_teams', 'fixtures', 'active_pick'));
    }

    public function list()
    {
        $season = current_season();
        $season_id = $season->id;

        // TODO: Add bail here for when no seasons found.

        $players = Player::with(['picks' => function($q) use($season_id) {
                $q->where('player_teams.season_id', $season_id);
                $q->orderByDesc('game_date');
            }])
            ->get()
            ->sortByDesc('name');

        return view('content.picks.list')
            ->with(compact('players', 'weeks'));
    }
}
