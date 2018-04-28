<?php

namespace App\Http\Requests;

use App\Rules\{ FixtureAvailable, NotAlreadyPickedByPlayer, NotPickingRivallingTeam, PlayerAlreadyPicked, ValidPlayerToken };
use Illuminate\Foundation\Http\FormRequest;

class PickSaveRequest extends FormRequest
{    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player_token' => ['bail', 'required', new ValidPlayerToken],
            'game_date' => 'bail|required|date',
            'fixture' => 'bail|required',
            'pick' => [
                'required',
                'integer',
                new PlayerAlreadyPicked,
                new NotAlreadyPickedByPlayer,
                new NotPickingRivallingTeam

            ]
        ];
    }

    public function messages() {
        return [
            'pick.required' => 'You must pick a team.',
            'pick.integer' => 'You must pick a valid team.'
        ];
    }
}
