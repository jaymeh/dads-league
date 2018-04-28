<?php

namespace App\Rules;

use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class PlayerAlreadyPicked implements Rule
{
    // TODO: Continue to implement multiple rules.
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

        $player_picked = PlayerTeam::playerAlreadyPicked($player->id, $team_id, $game_date)
            ->count();

        // If we don't have an existing pick for the player then we are fine.
        if(!$player_picked)
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
        return 'You have already picked this team in the past. Please pick a different one.';
    }
}
