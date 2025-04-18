<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * QuizResponse model represents the entity that defines
 * relation between team and quiz. If a team has participation
 * in a quiz, they are allowed to take quiz.
 */
class QuizResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['timeLeft'];

    /**
     * The attributes that have date type.
     * i.e. they are mutated to instance of Carbon.
     *
     * @var array
     */
    protected $dates = ['started_at', 'finished_at'];

    /**
     * All Responses for this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function responses()
    {
        return $this->hasMany(QuestionResponse::class);
    }

    /**
     * Quiz associated with this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Team associated with this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Evaluate Score for this quiz participation.
     *
     * @return int|bool
     */
    public function evaluate()
    {
        $question_ids = $this->quiz->questions->pluck('id', 'id');

        $score = $this->responses
            ->filter(fn ($response) => $question_ids->has($response->question_id))
            ->sum(fn ($response) => $response->score);

        return $this->update(compact('score')) ? $score : false;
    }

    /**
     * Accessor for time left (seconds) in finishing the quiz.
     *
     * @return int
     */
    public function getTimeLeftAttribute()
    {
        $time_spent = optional($this->started_at)->diffInSeconds(now(), false) ?? 0;

        return $this->quiz->time_limit - $time_spent;
    }
}
