<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Season;

class HomepageTest extends TestCase
{
    use WithFaker;

    public function setUp() 
    {
        parent::setUp();
        
        // Create a new season to use.
        factory(Season::class)->create();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
