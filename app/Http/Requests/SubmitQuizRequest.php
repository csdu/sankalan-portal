<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\QuizMustBeActive;
use App\Rules\TeamMustBeAllowedForQuiz;
use App\Rules\TeamHasNotTakenQuiz;
use App\Rules\TeamMustHaveStartedQuiz;

class SubmitQuizRequest extends FormRequest
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
            'responses' => ['sometimes', 'array', "max:{$this->quiz->questions()->count()}"],
            'responses.*.response_keys' => ['required', 'string'],
            'responses.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'quiz' => [ new QuizMustBeActive() ],
            'team' => [
                'required',
                new TeamMustBeAllowedForQuiz($this->quiz),
                new TeamHasNotTakenQuiz($this->quiz),
                new TeamMustHaveStartedQuiz($this->quiz),
            ]
        ];
    }

    public function messages()
    {
        return [
            'team.required' => 'Your team must participate in the event, before you can take quiz.',
        ];
    }

    protected function validationData()
    {
        $team = $this->quiz->event->participatingTeamByUser(auth()->user());
        
        return array_merge($this->all(), [
            'quiz' => $this->quiz,
            'team' => $team,
        ]);
    }
}
