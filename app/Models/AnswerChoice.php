<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
    /**
     * The attributes that are NOT mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Question to which this choice belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Is it correct choice?
     *
     * @return bool
     */
    public function isCorrect()
    {
        return $this->question->correct_answer_keys->contains($this->key);
    }

    /**
     * Accessor for related illustration's absolute path.
     *
     * @param string $illustration
     * @return string
     */
    public function getIllustrationAttribute($illustration)
    {
        if (! $illustration) {
            return $illustration;
        }

        return asset("/images$illustration");
    }

    /**
     * Accessor for answer's key, removes any special characters
     * and spaces to avoid mismatch correct answers.
     *
     * @param string $value
     * @return string
     */
    public function getKeyAttribute($key)
    {
        return strtolower(trim(
            preg_replace(
                '~[^a-zA-Z0-9]+~',
                '',
                preg_replace('~( |_|\-)+~', ' ', $key)
            )
        ));
    }
}
