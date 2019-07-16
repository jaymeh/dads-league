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
     * Tests the index page is working.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
