<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    use HasFactory;

    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['isActiveQuizAllowed'];
    protected $fillable = ['disqualified'];

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

    public function activeQuizResponse()
    {
        return $this->belongsTo(QuizResponse::class);
    }

    public function scopeWithActiveQuizResponse($query)
    {
        return $query->join('events', 'event_participations.event_id', '=', 'events.id')
            ->join('teams', 'event_participations.team_id', '=', 'teams.id')
            ->addSelect([
                'active_quiz_participation_id' => QuizResponse::select('id')->whereColumn(
                    'quiz_responses.quiz_id',
                    'events.active_quiz_id'
                )->whereColumn('quiz_responses.team_id', 'teams.id')
                    ->limit(1),
            ])->with('activeQuizResponse');
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

    public function setDisqualify()
    {
        return $this->update(['disqualified' => true]);
    }

    public function revertDisqualify()
    {
        return $this->update(['disqualified' => false]);
    }
}
