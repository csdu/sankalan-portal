<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TeamMustBeAllowedForQuiz implements Rule
{
    /**
     * @var \App\Quiz
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
     * @param  \App\Team  $team
     * @return bool
     */
    public function passes($attribute, $team)
    {
        return $this->quiz->participations()->where('team_id', $team->id)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not yet allowed for this quiz by admin!';
    }
}
