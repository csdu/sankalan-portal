<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'quiz_participations');
    }

    public function setActive() {
        $this->event->activeQuiz()->associate($this);
    }

    public function isTeamAllowed(Team $team) {
        $this->teams->pluck('id')->contains($team->id);
    }
}
