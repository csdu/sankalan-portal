<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizParticipation extends Model
{
    protected $guarded = [];

    protected $appends = ['timeLeft'];

    protected $dates = ['started_at', 'finished_at'];

    public function responses()
    {
        return $this->hasMany(QuizResponse::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getTimeLeftAttribute() {
        $timeSpent = optional($this->started_at)->diffInSeconds(now()) ?? 0;
        return $this->quiz->time_limit - $timeSpent;
    }

    public function evaluate() {
        $question_ids = $this->quiz->questions->pluck('id', 'id');

        $score = $this->responses->filter(function ($response) use ($question_ids) {
            return $question_ids->has($response->question_id);
        })->sum->score;
        
        return $this->update(compact('score')) ? $score : false;
    }

    public function recordResponses($responses) {
        return $this->responses()->createMany($responses);
    }
}
