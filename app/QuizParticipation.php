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

    public function getTimeLeftAttribute() {
        $timeSpent = optional($this->started_at)->diffInMinutes(now()) ?? 0;
        return $this->quiz->timeLimit - $timeSpent;
    }
}
