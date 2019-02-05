<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    public function team() 
    {
        return $this->belongsTo(Team::class);    
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
