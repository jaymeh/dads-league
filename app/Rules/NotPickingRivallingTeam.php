<?php

namespace App\Rules;

use App\Models\PickToken;
use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class NotPickingRivallingTeam implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Get token
        $token = request()->player_token;

        if(!$token)
        {
            return;
        }

        // Find player
        $player = PickToken::isActiveByToken($token)
            ->first();

        if(!$player)
        {
            return;
        }

        // TODO: We need the fixture to be updated to validate this correctly. If a team_id is just changed in the dom we do no other validation.

        // Get Fixture
        if(!$fixture_id = request()->fixture)
        {
            return;
        }
        
        // Get Game Date
        if(!$game_date = request()->game_date)
        {
            return;
        }

        $game_date = new Carbon($game_date);

        $rivalling_pick_count = PlayerTeam::pickingRivalingTeam($fixture_id, $game_date, $player->id)
            ->count();

        // If team hasn't been picked by another player this week then success.
        if(!$rivalling_pick_count)
        {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry, this team is playing against another which has been picked this week.';
    }
}
