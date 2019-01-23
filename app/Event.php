<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'participants');
    }
}
