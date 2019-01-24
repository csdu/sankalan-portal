<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'participants');
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
}
