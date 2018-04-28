<?php

namespace App\Rules;

use App\Models\PickToken;
use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class NotAlreadyPickedByPlayer implements Rule
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

        // Get Game Date
        if(!$game_date = request()->game_date)
        {
            return;
        }

        $game_date = new Carbon($game_date);

        // Assign team so we know what it is
        $team_id = $value;

        $picked_by_another_count = PlayerTeam::teamPickedByOtherPlayer($team_id, $player->id, $game_date)
            ->count();

        // If team hasn't been picked by another player this week then success.
        if(!$picked_by_another_count)
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
        return 'This team has already been picked by another player this week.';
    }
}
