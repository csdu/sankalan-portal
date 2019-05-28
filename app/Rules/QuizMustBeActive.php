<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class QuizMustBeActive implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  \App\Quiz  $value
     * @return bool
     */
    public function passes($attribute, $quiz)
    {
        return $quiz->isActive;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The quiz is not active yet. Please wait for the quiz to begin!';
    }
}
