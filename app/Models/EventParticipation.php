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

    public function activeQuizParticipation()
    {
        return $this->belongsTo(QuizParticipation::class);
    }

    public function scopeWithActiveQuizParticipation($query)
    {
        return $query->join('events', 'event_participations.event_id', '=', 'events.id')
            ->join('teams', 'event_participations.team_id', '=', 'teams.id')
            ->addSelect([
                'active_quiz_participation_id' => QuizParticipation::select('id')->whereColumn(
                    'quiz_participations.quiz_id',
                    'events.active_quiz_id'
                )->whereColumn('quiz_participations.team_id', 'teams.id')
                ->limit(1),
            ])->with('activeQuizParticipation');
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

        // return $this->event->activeQuiz->participations()->where('team_id', $this->team->id)->exists();
        return $this->event->activeQuiz->participations()->with('team');
    }
}
