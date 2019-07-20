<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Fixture;
use App\Models\PickToken;
use App\Models\Player;
use App\Models\PlayerTeam;
use Carbon\Carbon;

class PlayerDisabledTest extends TestCase
{
    /** @test */
    public function disabled_players_dont_appear_in_the_season_list()
    {
        $player = factory(Player::class)->create();
        $disabledPlayer = factory(Player::class)->create(['disabled' => 1]);

        $response = $this->get('/');
        $response->assertSeeText(e($player->name));
        $response->assertDontSeeText(e($disabledPlayer->name));
    }

    /** @test */
    public function disabled_players_dont_appear_on_pick_list_page() {
        $disabledPlayer = factory(Player::class)->create(['disabled' => 1]);

        $fixture = factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        factory(PlayerTeam::class)->create(['fixture_id' => $fixture->id, 'team_id' => $fixture->home_team_id, 'player_id' => $disabledPlayer->id]);
        // $team = Team::whereId($fixture->home_team_id)->first();

        $response = $this->get('/picks/list');
        $response->assertDontSeeText(e($disabledPlayer->name));
    }

    /** @test */
    public function disabled_players_dont_appear_on_league_table() {
        $disabledPlayer = factory(Player::class)->create(['disabled' => 1]);
        
        $response = $this->get('/league-table');
        $response->assertDontSeeText(e($disabledPlayer->name));
    }

    /** @test */
    public function disabled_players_dont_appear_on_pick_fixtures_page() 
    {
        $disabledPlayer = factory(Player::class)->create(['disabled' => 1]);

        $fixture = factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        factory(PlayerTeam::class)->create(['fixture_id' => $fixture->id, 'team_id' => $fixture->home_team_id, 'player_id' => $disabledPlayer->id]);
        // $team = Team::whereId($fixture->home_team_id)->first();

        $response = $this->get('/picks');
        $response->assertDontSeeText(e($disabledPlayer->name));
    }

    /** @test */
    public function disabled_players_cant_get_to_pick_page_even_with_valid_token() 
    {
        $disabledPlayer = factory(Player::class)->create(['disabled' => 1]);
        $pick_token = factory(PickToken::class)->create(['player_id' => $disabledPlayer->id]);
        
        $response = $this->get('picks/weekly/' . $pick_token->token);
        $response->assertRedirect('picks');
    }

    /** @test */
    public function an_option_to_disable_players_is_shown_on_creation() 
    {
        $user = factory(\App\Models\User::class)->create();

        $response = $this->actingAs($user)
            ->get('/players/create');
        
        $response->assertSee('Disabled');
        $response->assertSee('input type="checkbox" name="disabled"');
    }

    /** @test */
    public function storing_user_with_disabled_flag_in_post_request_saves_correctly() {
        $user = factory(\App\Models\User::class)->create();

        $player = factory(Player::class)->make(['disabled' => 1]);
        $playerArray = $player->toArray();
        
        $response = $this->actingAs($user)->post('/players', $playerArray);

        $this->assertDatabaseHas('players', $playerArray);
    }
}
