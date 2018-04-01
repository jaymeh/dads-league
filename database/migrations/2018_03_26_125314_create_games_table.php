<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_team_id');
            $table->integer('home_team_score');
            $table->integer('away_team_id');
            $table->integer('away_team_score');
            $table->integer('league_id');
            $table->date('game_date');
            $table->timestamps();

            $table->unique(['home_team_id', 'away_team_id', 'game_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
