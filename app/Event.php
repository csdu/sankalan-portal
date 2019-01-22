<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'participants');
    }

    public function participate($team_id)
    {
        return $this->teams()->attach($team_id);
    }
}
