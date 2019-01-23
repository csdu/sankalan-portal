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
        return $this->teams()->with('members')->get()->flatMap->members;
    }

    public function isAnyParticipating($members)
    {
        return !!$this->allParticipantMembers()->pluck('id')->intersect($members->pluck('id'))->count();
    }
}
