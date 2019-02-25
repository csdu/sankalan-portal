<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Quiz extends Model
{
    protected $guarded = [];

    protected $appends = ['isActive', 'isClosed'];

    protected $dates = ['closed_at', 'opened_at'];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'quiz_participations');
    }

    public function participations() {
        return $this->hasMany(QuizParticipation::class, 'quiz_id');
    }

    public function setActive() {
        if(!$this->event->isLive) {
            $this->event->setLive();
        }
        return $this->event->update(['active_quiz_id' => $this->id]) &&
            $this->update(['opened_at' => now()]);
    }

    public function setOffline() {
        return $this->event->update(['active_quiz_id' => null]) &&
            $this->update(['closed_at' => now()]);
    }

    public function setInstructionsAttribute($instructions) {
        if($instructions instanceof Collection) {
            $instructions = $instructions->toArray();
        }
        if(is_array($instructions)) {
            $this->attributes['instructions'] = implode("\n", $instructions);
        }
    }

    public function getInstructionsAttribute($instructions)
    {
        if($instructions) {
            return explode("\n", $instructions);
        }
        return [];
    }
    
    public function getIsActiveAttribute() {
        if($this->event_id === null) {
            return null;
        }
        
        return $this->id == $this->event->active_quiz_id &&
            $this->opened_at && !$this->closed_at;
    }

    public function getIsClosedAttribute()
    {
        return !!$this->closed_at;
    }

    public function isTeamAllowed(Team $team) {
        return $this->teams->pluck('id')->contains($team->id);
    }

    public function isTimeLimitExceeded($team) {
        if(! $participation = $this->participationByTeam($team)) {
            return false;
        }
        $timeTaken = optional($participation->started_at)->diffInSeconds(now());
        return $timeTaken > ($this->time_limit + 1*60); // Add 1 Minute Extra
    }

    public function allowTeam(Team $team) {
        return $this->teams()->attach($team);
    }

    public function participationByTeam(Team $team) {
        return $this->participations()->where('team_id', $team->id)->first();
    }
    
    public function recordResponses(Team $team, $responses) {
        return $this->participationByTeam($team)->recordResponses($responses);
    }

    public function hasTeamResponded(Team $team) {
        return optional($this->participationByTeam($team))->finished_at != null;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
