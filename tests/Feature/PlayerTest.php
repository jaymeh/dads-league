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
			->assertSeeText(e($player->name));
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
				'name' => $name,
				'email' => $email
			]);

		$this->assertDatabaseHas('players', [
			'name' => $name,
			'email' => $email
		]);
	}

	/** @test */
	public function an_unauthenticated_user_cannot_delete_players()
	{
		$player = factory(\App\Models\Player::class)->create();

		$response = $this
			->delete(route('players.destroy', $player));

		$response->assertRedirect('/login');

		$this->assertDatabaseHas('players', [
			'name' => $player->name,
			'email' => $player->email
		]);
	}

	/** @test */
	public function an_authenticated_user_can_delete_players()
	{
		$player = factory(\App\Models\Player::class)->create();
		$user = factory(\App\Models\User::class)->create();

		$response = $this
			->actingAs($user)
			->delete(route('players.destroy', $player));

		$response->assertRedirect(route('players.index'));

		$this->assertDatabaseMissing('players', [
			'name' => $player->name,
			'email' => $player->email
		]);
	}

	/** @test */
	public function an_unauthenticated_user_cannot_edit_players()
	{
		$player = factory(\App\Models\Player::class)->create();

		$new_player = $player;
		$new_player->name = $this->faker->name;
		$new_player->email = $this->faker->safeEmail;

		$response = $this
			->put(route('players.update', $new_player));

		$response->assertRedirect('/login');

		$this->assertDatabaseMissing('players', [
			'name' => $new_player->name,
			'email' => $new_player->email
		]);
	}

	/** @test */
	public function an_authenticated_user_can_edit_players()
	{
		$player = factory(\App\Models\Player::class)->create();
		$user = factory(\App\Models\User::class)->create();

		$new_player_name = $this->faker->name;
		$new_player_email = $this->faker->safeEmail;

		$response = $this
			->actingAs($user)
			->patch(route('players.update', $player->id), [
				'name' => $new_player_name,
				'email' => $new_player_email
			]);

		$response->assertRedirect(route('players.index'));

		$this->assertDatabaseHas('players', [
			'name' => $new_player_name,
			'email' => $new_player_email
		]);

		$this->assertDatabaseMissing('players', [
			'name' => $player->name,
			'email' => $player->email
		]);
	}
}
