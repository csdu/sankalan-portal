<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * QuizResponse model represents the entity that defines
 * relation between team and quiz. If a team has participation
 * in a quiz, they are allowed to take quiz.
 *
 * @property \DateTimeInterface|null $started_at
 * @property \DateTimeInterface|null $finished_at
 */
class QuizResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The attributes that are appended for array.
     *
     * @var array<string>
     */
    protected $appends = ['timeLeft'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'integer'
    ];

    /**
     * All Responses for this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responses()
    {
        return $this->hasMany(QuestionResponse::class);
    }

    /**
     * Quiz associated with this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Team associated with this quiz participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Evaluate Score for this quiz participation.
     *
     * @return int|false
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
        if (!$this->started_at) {
            return $this->quiz->time_limit;
        }

        $time_spent = $this->started_at->diffInSeconds(Date::now());

        return max(0, $this->quiz->time_limit - $time_spent);
    }
}
