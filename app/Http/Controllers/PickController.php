<?php

namespace App\Http\Controllers;

use App\Http\Requests\PickSaveRequest;
use App\Models\Fixture;
use App\Models\League;
use App\Models\PickToken;
use App\Models\Player;
use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PickController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->except(['weeklyPick']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all the picks, this season... and group them.
        $season = current_season();
        $season->load('picks.player');
        $picks = $season->picks;

        $next_date = new Carbon('this saturday');

        $has_picks = $picks->where('game_date', $next_date);

        $this_week_selection = false;
        if(!$has_picks->count())
        {
            $this_week_selection = $next_date;
        }

        $picks_by_date = $picks->groupBy(function($pick) {
            return $pick->game_date->format('d-m-Y');
        });

        return view('content.picks.index')
            ->with(compact('season', 'picks_by_date', 'this_week_selection'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PickSaveRequest $request)
    {
        $picks = $request->players;
        $date = $request->game_date;

        // Get current season.
        $season = current_season();

        // Loop through all the picks + save them
        foreach($picks as $player_id => $pick)
        {
            $existing_pick = PlayerTeam::updateOrCreate([
                'player_id' => $player_id, 
                'game_date' => $date,
                'season_id' => $season->id
            ], [
                'team_id' => $pick
            ]);
        }

        trigger_message('Successfully saved/updated this weeks picks.', 'success');

        return redirect()->back();
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
        $now = Carbon::today();

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

            return redirect()->route('index');
        }

        if(!$player_token->active)
        {
            // Invalid token given.
            trigger_message('You have already picked your team this week.', 'error');

            return redirect()->route('index');
        }

        $grouped_fixtures = League::with([
                'fixtures' => function($q) use($this_saturday) {
                    $q->whereDate('game_date', $this_saturday);
                }
            ])
            ->with('fixtures.homeTeam', 'fixtures.awayTeam')
            ->get();

        $all_teams_by_league = $grouped_fixtures->map(function($league) use ($this_saturday) {
            return $league->fixtures->where('game_date', $this_saturday->format('Y-m-d'))->map(function($teams) {
                $team_names = [$teams->homeTeam->id => [
                    'id' => $teams->homeTeam->id,
                    'name' => $teams->homeTeam->name,
                    'logo' => $teams->homeTeam->logo,
                    'league' => $teams->homeTeam->league_id
                ], $teams->awayTeam->id => [
                    'id' => $teams->awayTeam->id,
                    'name' => $teams->awayTeam->name,
                    'logo' => $teams->awayTeam->logo,
                    'league' => $teams->awayTeam->league_id
                ]];

                return $team_names;
            });
        });

        $all_teams = $all_teams_by_league->flatten(2);

        // dd($all_teams);

        $player_picks = $player_token->player->picks;

        $previous_picks_by_player = Player::with([
            'picks' => function($q) use ($this_saturday, $season) {
                $q->where('game_date', '!=', $this_saturday);
                $q->where('season_id', $season->id);
            }])
            ->get()
            ->mapWithKeys(function($player, $key) {
                return [$player->id => $player->picks->pluck('id')];
            });

        // Remove anything that someone else picked.

        $player = $player_token->player;

        return view('content.picks.weekly')->with(compact('this_saturday', 'token', 'grouped_fixtures', 'player', 'all_teams'));

        // dd($player_token);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($the_game_date)
    {
        $the_game_date = new Carbon($the_game_date);
        $leagues_with_teams = League::whereHas('fixtures', function($query) use ($the_game_date) {
                $query->where('game_date', $the_game_date);
            })
            ->with('fixtures.homeTeam','fixtures.awayTeam')
            ->orderBy('position')
            ->get();

        $season = current_season();

        $all_teams_by_league = $leagues_with_teams->map(function($league) {

            return $league->fixtures->map(function($teams) {
                $team_names = [$teams->homeTeam->id => [
                    'id' => $teams->homeTeam->id,
                    'name' => $teams->homeTeam->name,
                    'logo' => $teams->homeTeam->logo,
                    'league' => $teams->homeTeam->league_id
                ], $teams->awayTeam->id => [
                    'id' => $teams->awayTeam->id,
                    'name' => $teams->awayTeam->name,
                    'logo' => $teams->awayTeam->logo,
                    'league' => $teams->awayTeam->league_id
                ]];

                return $team_names;
            });
        });

        $all_teams = $all_teams_by_league->flatten(2);

        $players_with_picks = Player::with(['picks' => function($q) use ($the_game_date) {
                $q->where('game_date', $the_game_date);
            }])
            ->get();

        $player_existing_picks = $players_with_picks
            ->mapWithKeys(function($player, $key) {
                return [$player->id => $player->picks->pluck('id')->first()];
            });

        $previous_picks_by_player = Player::with([
            'picks' => function($q) use ($the_game_date, $season) {
                $q->where('game_date', '!=', $the_game_date);
                $q->where('season_id', $season->id);
            }])
            ->get()
            ->mapWithKeys(function($player, $key) {
                return [$player->id => $player->picks->pluck('id')];
            });

        return view('content.picks.edit')
            ->with(compact('leagues_with_teams', 'all_teams', 'the_game_date', 'players_with_picks', 'previous_picks_by_player', 'player_existing_picks'));
    }
}
