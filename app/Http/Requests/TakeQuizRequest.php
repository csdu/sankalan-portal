<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\QuizMustBeActive;
use App\Rules\TeamHasNotTakenQuiz;
use App\Rules\TeamMustBeAllowedForQuiz;

class TakeQuizRequest extends FormRequest
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
            'quiz' => [ new QuizMustBeActive() ],
            'team' => [ 
                'required',
                new TeamMustBeAllowedForQuiz($this->quiz),
                new TeamHasNotTakenQuiz($this->quiz),
            ],
        ];
    }

    public function messages()
    {
        return [
            'team.required' => 'You must participate in event before taking quiz.'
        ];
    }

    protected function validationData()
    {
        $team = $this->quiz->event->participatingTeamByUser(auth()->user());
        
        return [
            'team' => $team,
            'quiz' => $this->quiz
        ];
    }
}
