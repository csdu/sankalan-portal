<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionResponse extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Question associated with this response.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Is it given choice chosen?
     *
     * @param QuestionOption $choice
     * @return bool
     */
    public function isChosen(QuestionOption $choice)
    {
        return $this->response_keys === $choice->key;
    }

    /**
     * Accessor gives calculated score for this response.
     *
     * @return int Score
     */
    public function getScoreAttribute()
    {
        if ($this->question->correct_answer_keys->contains($this->response_keys)) {
            return $this->question->positive_score;
        }

        return -$this->question->negative_score;
    }

    /**
     * Response keys accessor, removes any special characters
     * and spaces to avoid mismatch correct answers.
     *
     * @param string $value
     * @return string
     */
    public function getResponseKeysAttribute($value)
    {
        return strtolower(trim(
            preg_replace(
                '~[^a-zA-Z0-9]+~',
                '',
                preg_replace('~( |_|\-)+~', ' ', $value)
            )
        ));
    }
}
