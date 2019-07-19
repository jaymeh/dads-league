<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\League;
use App\Models\Fixture;
use App\Models\PickToken;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Team;
use Illuminate\Support\Carbon;

class PickTest extends TestCase
{
    /** @test */
    public function has_leagues() {
        $this->assertGreaterThan(1, League::all()->count());
    }
    /**
     * @test
     * 
     *
     * @return void
     */
    public function if_a_player_hasnt_picked_a_team_we_cant_see_one()
    {
        // Create a fixture.
        $fixture = factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        // Create a player
        $player = factory(Player::class)->create();
        
        $team = Team::whereId($fixture->home_team_id)->first();

        $response = $this->get('/picks');
        $response->assertDontSeeText($player->name);
        $response->assertDontSeeText($team->name);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function if_a_player_has_picked_a_team_we_can_see_it()
    {
        // Create a fixture.
        $fixture = factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        // Create a player
        $player = factory(Player::class)->create();
        $pick = factory(PlayerTeam::class)->create(['fixture_id' => $fixture->id, 'team_id' => $fixture->home_team_id, 'player_id' => $player->id]);

        $team = Team::whereId($fixture->home_team_id)->first();

        $response = $this->get('/picks');
        $response->assertSeeText($player->name);
        $response->assertSeeText($team->name);
    }

    /** @test */
    public function check_player_cant_see_not_picked_and_can_see_picked() {
        $fixture = factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);
        $player = factory(Player::class)->create();

        $team = Team::whereId($fixture->away_team_id)->first();

        $response = $this->get('/picks/list');

        $response->assertDontSeeText(e($player->name));
        $response->assertDontSeeText($team->name);

        factory(PlayerTeam::class)->create(['fixture_id' => $fixture->id, 'team_id' => $fixture->away_team_id, 'player_id' => $player->id]);

        $response = $this->get('/picks/list');
        $response->assertSeeText(e($player->name));
        $response->assertSeeText($team->name);
    }

    /** @test */
    public function if_no_token_exists_weekly_picks_page_returns_not_found() {
        $response = $this->get('/picks/weekly/jldfjklsfksdjfklasfjl');
        $response->assertStatus(302);
        $response->assertRedirect('/picks');
    }

    /** @test */
    public function if_token_exists_assert_we_get_pick_page() {
        $player = factory(Player::class)->create();
        $token = factory(PickToken::class)->create(['player_id' => $player->id]);

        $league_id = League::get()->first()->id;

        $fixture = factory(Fixture::class)->create(['league_id' => $league_id]);

        $response = $this->get("/picks/weekly/" . $token->token);
        $response->assertStatus(200);
        $response->assertSeeText(e($player->name));
        $response->assertSee($fixture->home_team_id);
    }

    // TODO: Test for team not appearing in list that shouldn't
}
