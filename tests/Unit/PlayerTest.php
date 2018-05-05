<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Player extends TestCase
{
	use WithFaker;

	/** @test */
	public function an_unauthenticated_user_cannot_see_player_list()
	{
		// Go to player page when logged out
		// We should be redirected to the login page
		$response = $this->get(route('players.index'));

		$response
			->assertRedirect('/login');
	}

	/** @test */
	public function an_authenticated_user_can_see_players()
	{
		// Mock a player.
		$user = factory(\App\Models\User::class)->create();
		$player = factory(\App\Models\Player::class)->create();

		$response = $this->actingAs($user)
			->get(route('players.index'));

		$response->assertStatus(200)
			->assertSee($player->name);
	}

	/** @test */
	public function an_unauthenticated_user_cannot_add_players()
	{
		$name = $this->faker->name;
		$email = $this->faker->email;

		$response = $this
			->post(route('players.store'), [
				$name,
				$email
			]);

		$response->assertRedirect('/login');

		$this->assertDatabaseMissing('players', [
			'name' => $name,
			'email' => $email
		]);
	}

	/** @test */
	public function an_authenticated_user_can_add_players()
	{
		$user = factory(\App\Models\User::class)->create();

		$name = $this->faker->name;
		$email = $this->faker->email;

		$response = $this
			->actingAs($user)
			->post(route('players.store'), [
				$name,
				$email
			]);

		dd($response->assertRedirect('/players'));

		// $response->assertRedirect('/login');

		$this->assertDatabaseHas('players', [
			'name' => $name,
			'email' => $email
		]);
	}
}
