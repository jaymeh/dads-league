<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Player;
use App\Models\Season;
use App\Models\Table;

class LeagueTableTest extends TestCase
{
    /** @test */
    public function assert_random_user_appears_in_score_table()
    {
        $player = factory(Player::class)->create();
        
        $response = $this->get('league-table');
        $response->assertStatus(200);
        $response->assertSeeText(e($player->name));
        
        $response_data = $response->getOriginalContent()->getData();
        $this->assertTrue(isset($response_data['league_table'][$player->name]));

        $found = $response_data['league_table'][$player->name];

        $this->assertEquals($found->score, 0);
        $this->assertEquals($found->wins, 0);
        $this->assertEquals($found->draws, 0);
        $this->assertEquals($found->losses, 0);
    }

    /** @test */
    public function assert_league_table_displays_right_scores() 
    {
        $season = Season::get()->first();
        $table_entry = factory(Table::class)->create(['season_id' => $season->id]);

        $response = $this->get('league-table');
        $response->assertStatus(200);
        $response->assertSeeText(e($table_entry->player->name));

        $player = $table_entry->player;

        $response_data = $response->getOriginalContent()->getData();
        $found = $response_data['league_table'][$player->name];

        $this->assertEquals($found->score, $table_entry->score);
        $this->assertEquals($found->wins, $table_entry->wins);
        $this->assertEquals($found->draws, $table_entry->draws);
        $this->assertEquals($found->losses, $table_entry->losses);
    }

    // TODO: Assert that the score equals what it should based on the number of games won.
}
