<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;

class PlayerTest extends TestCase
{
    use WithFaker;

    /**
     * Tests when you are not authenticated, you can't access 
     * player screens.
     */
    public function testUnauthenticatedPlayersRedirects() 
    {
        // Failure getting player index.
        $response = $this->get('/players');
        $response->assertRedirect('/login');

        // Failure getting create screen.
        $response = $this->get('/players/create');
        $response->assertRedirect('/login');

        // Failure creating player.
        $playerEmail = $this->faker->email;
        $newPlayer = [
            'name' => $this->faker->name,
            'email' => $playerEmail
        ];

        $response = $this->post('/players', $newPlayer);
        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('players', ['email' => $playerEmail]);
    }

    /**
     * Tests that when you are authenticated you can 
     * access the player index.
     */
    public function testAuthenticatedPlayersIndex() 
    {
        // Setup user.
        $authenticatableUser = factory(User::class)->create();

        // Test you can get to the player list.
        $response = $this->actingAs($authenticatableUser)->get('/players');
        $response->assertStatus(200);
    }

    /**
     * Tests you can create a new player when authenticated.
     */
    public function testAuthenticatedPlayersCreate() 
    {
        // Setup player.
        $playerEmail = $this->faker->email;
        $newPlayer = [
            'name' => $this->faker->name,
            'email' => $playerEmail
        ];

        // Add user and ensure they have been created.
        $authenticatableUser = factory(User::class)->create();
        $response = $this->actingAs($authenticatableUser)->post('/players', $newPlayer);
        $response->assertRedirect('/players');

        $this->assertDatabaseHas('players', ['email' => $playerEmail]);
    }
}
