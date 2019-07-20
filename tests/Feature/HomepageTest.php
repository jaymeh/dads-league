<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class HomepageTest extends TestCase
{
    use WithFaker;

    public function setUp() 
    {
        parent::setUp();
    }
    /**
     * @test
     * Tests the index page is working.
     *
     * @return void
     */
    public function the_index_page_returns_a_successful_response()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
