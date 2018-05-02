<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('players', 'PlayerController')->except([
    'show'
]);

Route::prefix('picks')->group(function () {
    Route::get('/', 'PickController@index')->name('picks.index');
	Route::post('/', 'PickController@store')->name('picks.store');
	Route::get('list', 'PickController@list')->name('picks.list');
	Route::get('weekly/{token}', 'PickController@weeklyPick')->name('picks.weekly');
});

Route::get('league-table', 'LeagueController@index')->name('league-table');

Route::get('weekly-result-mail', function() {
	$last_week = new Carbon\Carbon('last saturday');
	$results = App\Models\PlayerTeam::whereHas('team')
            ->with('player', 'team', 'fixture.game')
            ->where('game_date', $last_week)
            ->get();

    $season = current_season();

    $table = App\Models\Table::where('season_id', $season->id)
        ->with('player')
        ->orderByDesc('score')
        ->get()
        ->mapWithKeys(function($table) {
            return [$table->player->name => $table];
        });

    // dd($table);

    return new App\Mail\WeeklyResults($results, $table);
});

