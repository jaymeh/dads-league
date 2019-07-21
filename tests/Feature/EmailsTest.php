<?php

namespace Tests\Unit;

use Carbon\Carbon;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Mail\LastChancePickReminder;
use App\Mail\PickReminder;

use App\Models\Fixture;
use App\Models\Season;
use App\Mail\WeeklyResults;

class EmailsTest extends TestCase
{
    /** @test */
    public function test_last_chance_pick_emails_dont_go_out_to_disabled_players()
    {
        Mail::fake();

        $player = factory(\App\Models\Player::class)->create(['disabled' => 1]);

        $token = factory(\App\Models\PickToken::class)->create(['player_id' => $player->id]);
        \Artisan::call('cron:last-chance-pick-reminder');

        Mail::assertNotSent(LastChancePickReminder::class, function ($mail) {
            return true;
        });
    }

    /** @test */
    public function assert_pick_reminders_go_to_active_players() {
        Mail::fake();
        
        $player = factory(\App\Models\Player::class)->create();

        factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        $season = Season::get()->first();

        \Artisan::call('cron:send-weekly-picks', ['season_id' => $season->id]);

        Mail::assertSent(PickReminder::class, function ($mail) use ($player){
            return $mail->hasTo($player->email);
        });
    }

    /** @test */
    public function assert_pick_reminders_dont_go_out_to_disabled_players() {
        Mail::fake();
        
        $player = factory(\App\Models\Player::class)->create(['disabled' => 1]);

        factory(Fixture::class)->create(['game_date' => new Carbon('this saturday')]);

        $season = Season::get()->first();

        \Artisan::call('cron:send-weekly-picks', ['season_id' => $season->id]);

        Mail::assertNotSent(PickReminder::class, function ($mail) use ($player){
            return $mail->hasTo($player->email);
            // retur;
        });
    }

    /** @test */
    public function check_weekly_results_dont_get_sent_to_disabled_players() 
    {
        Mail::fake();

        $season = Season::get()->first();

        // Create 2 fixtures from last sat.
        $fixture = factory(\App\Models\Fixture::class)->create(['game_date' => new Carbon('last saturday')]);
        $fixture2 = factory(\App\Models\Fixture::class)->create(['game_date' => new Carbon('last saturday')]);
        
        // Create 2 games in same league last saturday
        factory(\App\Models\Game::class)->create(['game_date' => new Carbon('last saturday')]);
        // factory(\App\Models\Game::class)->create(['game_date' => new Carbon('last saturday')]);

        $player = factory(\App\Models\Player::class)->create(['disabled' => 1]);

        $pick_token = factory(\App\Models\PlayerTeam::class)->create(['player_id' => $player->id, 'season_id' => $season->id, 'fixture_id' => $fixture->id, 'team_id' => $fixture->home_team_id, 'game_date' => new Carbon('last saturday')]);
        $table = factory(\App\Models\Table::class)->create(['season_id' => $season->id, 'player_id' => $player->id]);

        $player2 = factory(\App\Models\Player::class)->create(['disabled' => 0]);

        $pick_token = factory(\App\Models\PlayerTeam::class)->create(['player_id' => $player2->id, 'season_id' => $season->id, 'fixture_id' => $fixture2->id, 'team_id' => $fixture2->home_team_id, 'game_date' => new Carbon('last saturday')]);
        $table = factory(\App\Models\Table::class)->create(['season_id' => $season->id, 'player_id' => $player2->id]);

        \Artisan::call('cron:weekly-results-email');

        Mail::assertSent(WeeklyResults::class, function ($mail) use ($player) {
            return !$mail->hasTo($player->email) &&
            !$mail->results->whereIn('player.email', $player->email)->count() >= 1 && 
            !$mail->table->whereIn('player.email', $player->email)->count() >= 1;
        });
    }

    // TODO: Check fixture count in the send-weekly-picks command.
}
