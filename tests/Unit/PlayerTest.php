<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Player extends TestCase
{
	public function test_an_unauthenticated_user_cannot_see_player_list()
	{
		// Go to player page when logged out
		// We should be redirected to the login page
		$response = $this->get('/players');

		$response
			->assertRedirect('/login');
	}

	public function test_an_authenticated_user_can_see_players()
	{
		// Mock a player.
		$user = factory(\App\Models\User::class)->create();
		$player = factory(\App\Models\Player::class)->create();

		$response = $this->actingAs($user)
			->get('/players');

		$response->assertStatus(200)
			->assertSee($player->name);
	}
}
