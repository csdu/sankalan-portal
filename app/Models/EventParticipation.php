<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['isActiveQuizAllowed'];

    /**
     * Team associated with event participation.
     *
     * @return void
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Event associated with the event participation.
     *
     * @return void
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Is active quiz allowed?
     *
     * @return bool|null
     */
    public function getIsActiveQuizAllowedAttribute()
    {
        if (! $this->event->activeQuiz) {
            return;
        }

        return $this->event->activeQuiz->participations()->where('team_id', $this->team->id)->exists();
    }
}
