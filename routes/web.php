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

Route::resource('picks', 'PickController')->except([
    'show',
    'update',
    'destroy',
    'create',
    'edit'
]);

Route::get('picks/weekly/{token}', ['uses' => 'PickController@weeklyPick', 'as' => 'weekly-pick']);