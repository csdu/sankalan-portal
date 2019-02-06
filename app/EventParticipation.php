<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    protected $appends = ['isActiveQuizAllowed'];
    
    public function team() 
    {
        return $this->belongsTo(Team::class);    
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getIsActiveQuizAllowedAttribute()
    {
        if(!$this->event->activeQuiz) {
            return null;
        }

        return $this->event->activeQuiz->participations()->where('team_id', $this->team->id)->exists();
    }
}
