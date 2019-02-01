<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'participants');
    }

    public function activeQuiz()
    {
        return $this->belongsTo(Quiz::class);
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
        if($this->isLive()) {
            return true;
        }

        return $this->update(['is_live' => true]);
    }

    public function isLive() {
        return !!$this->is_live;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
