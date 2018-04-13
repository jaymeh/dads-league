<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerAddRequest;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
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
    public function index()
    {
        $players = Player::all();

        return view('content.players.index')
            ->with(compact('players'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.players.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlayerAddRequest $request)
    {
        $input = $request->only(['name', 'email']);

        $player = new Player;
        $player->fill($input);
        $player->save();

        trigger_message('Successfully Added Player: ' . $request->name, 'success');

        return redirect()->route('players.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        return view('content.players.form')
            ->with(compact('player'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerAddRequest $request, $id)
    {
        $player = Player::find($id);
        $player->name = $request->name;
        $player->email = $request->email;
        $player->save();

        trigger_message('Successfully Updated Player: ' . $request->name, 'success');

        return redirect()->route('players.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        trigger_message('Successfully Deleted Player: ' . $player->name, 'success');

        $player->delete();

        return redirect()->route('players.index');
    }
}
