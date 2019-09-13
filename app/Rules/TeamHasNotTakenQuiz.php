<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TeamHasNotTakenQuiz implements Rule
{
    /**
     * @var \App\Models\Quiz
     */
    protected $quiz;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function passes($attribute, $team)
    {
        $participation = $this->quiz->participationByTeam($team);

        return $participation && ! $participation->finished_at;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have already taken this quiz.';
    }
}
