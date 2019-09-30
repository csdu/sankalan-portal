<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are NOT mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['uid'];

    /**
     * All members associated with this team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * All events in which team has participated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participations');
    }

    /**
     * Take part in the event.
     *
     * @param \App\Models\Event $event
     * @return bool true if participation successful, else false.
     */
    public function participate(Event $event)
    {
        if ($event->isAnyParticipating($this->members)) {
            return false;
        }
        $this->events()->syncWithoutDetaching($event->id);

        return true;
    }

    /**
     * Withdraw participation form the event.
     *
     * @param \App\Models\Event $event
     * @return bool
     */
    public function withdrawParticipation(Event $event)
    {
        $this->events()->detach($event->id);

        return true;
    }

    /**
     * End Quiz, submit all responses, update finish time.
     *
     * @param \App\Models\Quiz $quiz
     * @param array $responses
     * @return \App\Models\QuizResponse
     */
    public function endQuiz(Quiz $quiz, array $responses)
    {
        $participation = $quiz->participationByTeam($this);
        $responses = $participation->recordResponses($responses);
        $participation->update(['finished_at' => now()]);

        return $responses;
    }

    /**
     * Start quiz, record start time.
     *
     * @param Quiz $quiz
     * @return bool
     */
    public function beginQuiz(Quiz $quiz)
    {
        $participation = $quiz->participationByTeam($this);
        if(! $participation) {
            $participation = $quiz->participations()->create([
                'team_id' => $this->id,
                'started_at' => now(),
            ]);
        }

        if (! $participation->started_at) {
            return $participation->update(['started_at' => now()]);
        }

        return false;
    }

    /**
     * UID accessor for fancy prefixed Ids - `SNKLN-T-{id}`.
     *
     * @return string
     */
    public function getUidAttribute()
    {
        return env('ID_PREFIX', 'SNKLN').'-T'.str_pad("$this->id", 3, '0', STR_PAD_LEFT);
    }
}
