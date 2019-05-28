<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
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
        $userEmail = $this->user()->email;

        return [
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'member_email' => ['nullable', 'email', "not_in:{$userEmail}", 'exists:users,email'],
        ];
    }

    public function messages()
    {
        return [
            'member_email.not_in' => 'You cannot teamup with yourself.',
        ];
    }
}
