<?php

namespace App\Http\Controllers;

use App\Http\Requests\PickSaveRequest;
use App\Models\AvailableTeam;
use App\Models\League;
use App\Models\Player;
use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PickController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this_saturday = new Carbon('this saturday');
        $leagues_with_teams = League::whereHas('availableTeams', function($query) use ($this_saturday) {
                $query->where('game_date', $this_saturday);
            })
            ->with('availableTeams.homeTeam','availableTeams.awayTeam')
            ->orderBy('position')
            ->get();

        $all_teams_by_league = $leagues_with_teams->map(function($league) {

            return $league->availableTeams->map(function($teams) {
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

        $players_with_picks = Player::with(['picks' => function($q) use ($this_saturday) {
                $q->where('game_date', $this_saturday);
            }])
            ->get();

        $player_existing_picks = $players_with_picks
            ->mapWithKeys(function($player, $key) {
                return [$player->id => $player->picks->pluck('id')->first()];
            });

        $previous_picks_by_player = Player::with([
            'picks' => function($q) use ($this_saturday) {
                $q->where('game_date', '!=', $this_saturday);
            }])
            ->get()
            ->mapWithKeys(function($player, $key) {
                return [$player->id => $player->picks->pluck('id')];
            });

        return view('content.picks.index')
            ->with(compact('leagues_with_teams', 'all_teams', 'this_saturday', 'players_with_picks', 'previous_picks_by_player', 'player_existing_picks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        // Loop through all the picks + save them
        foreach($picks as $player_id => $pick)
        {
            $existing_pick = PlayerTeam::updateOrCreate([
                'player_id' => $player_id, 
                'game_date' => $date
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
