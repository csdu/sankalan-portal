<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teams()
    {
        return $this->morphedByMany(Team::class, 'participant');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'participant');
    }

    public function participateAsTeam($team_id)
    {
        return $this->teams()->attach($team_id);
    }

    public function participate()
    {
        return $this->users()->attach(auth()->id());
    }
}
