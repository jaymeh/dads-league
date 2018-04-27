<?php

namespace App\Rules;

use App\Models\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class FixtureAvailable implements Rule
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
        // dd();
        // Find the user.
        // Use with the team to determine if we are allowed to pick.
        // dd(request()->player_token);
        $token = request()->player_token;
        $game_date = new Carbon(request()->game_date);
        // dd(request());
        $fixture_id = request()->fixture;

        return PlayerTeam::canPickTeam($value, $token, $game_date, $fixture_id);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This team has already been picked by another player or yourself in the past.';
    }
}
