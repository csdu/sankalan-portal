<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $guarded = [];

    protected $dates = ['started_at', 'ended_at'];

    protected $appends = ['isLive', 'hasEnded'];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'event_participations');
    }

    public function activeQuiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function allowActiveQuiz(Team $team)
    {
        if(!$this->active_quiz_id) {
            return false;
        }

        $this->activeQuiz->allowTeam($team);
        return true;
    }

    public function allParticipantMembers()
    {
        return $this->loadMissing('teams.members')->teams->flatMap->members;
    }

    public function isAnyParticipating($members)
    {
        return !!$this->allParticipantMembers()->pluck('id')->intersect($members->pluck('id'))->count();
    }

    public function participatingTeamByUser($user)
    {
        return $this->loadMissing('teams.members')->teams->first(function($team) use ($user){
            return $team->members->pluck('id')->contains($user->id);
        });
    }

    public function setLive() {
        if($this->isLive) {
            return true;
        }

        return $this->update(['started_at' => now()]);
    }

    public function end()
    {
        if ($this->isLive) {
            return $this->update(['ended_at' => now()]);
        }
        
        return true;
    }

    public function getIsLiveAttribute() {
        return $this->hasStarted && ! $this->hasEnded;
    }

    public function getHasStartedAttribute()
    {
        return !!$this->started_at;
    }
    
    public function getHasEndedAttribute()
    {
        return !!$this->ended_at;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
