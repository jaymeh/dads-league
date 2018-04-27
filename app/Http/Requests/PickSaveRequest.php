<?php

namespace App\Http\Requests;

use App\Rules\FixtureAvailable;
use Illuminate\Foundation\Http\FormRequest;

class PickSaveRequest extends FormRequest
{
    // TODO: Add extra validation to make sure someone can't pick the same team twice.
    
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
            'pick' => [
                'required',
                'integer',
                new FixtureAvailable
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
