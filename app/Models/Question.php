<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['correct_answer_keys'];

    /**
     * The atrributes that are casted to desired type.
     *
     * @var array
     */
    protected $casts = [
        'positive_score' => 'integer',
        'negative_score' => 'integer',
    ];

    /**
     * all choices available for this question.s.
     *
     * @return void
     */
    public function choices()
    {
        return $this->hasMany(QuestionOption::class);
    }

    /**
     * Mutator implodes array of answer keys to string.
     *
     * @param array|string $value
     * @return void
     */
    public function setCorrectAnswerKeysAttribute($value)
    {
        if (is_array($value)) {
            $value = implode(':', $value);
        }

        $this->attributes['correct_answer_keys'] = $value ?? '';
    }

    /**
     * Accessor for illustraton's absolute path.
     *
     * @param string $illustration
     * @return string
     */
    public function getIllustrationAttribute($illustration)
    {
        if (! $illustration) {
            return $illustration;
        }

        return asset("/images{$illustration}");
    }

    /**
     * Accessor to get all the correct answer keys in a collection
     * all keys are converted to lowercase after removing all
     * special characters and spaces.
     *
     * @param string $keys
     * @return \Illuminate\Support\Collection
     */
    public function getCorrectAnswerKeysAttribute($keys)
    {
        return collect(explode(':', $keys))->map(function ($answer_key) {
            return strtolower(trim(
                preg_replace(
                    '~[^a-zA-Z0-9]+~',
                    '',
                    preg_replace('~( |_|\-)+~', ' ', $answer_key)
                )
            ));
        });
    }

    public function attachments()
    {
        return $this->hasMany(QuestionAttachment::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function userResponse()
    {
        return $this->belongsTo(QuestionResponse::class, 'user_response_id');
    }
}
