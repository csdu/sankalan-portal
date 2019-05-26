<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Quiz extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['isActive', 'isClosed'];

    /**
     * The attributes that are of DateTime type, mutated to Carbon's instance.
     *
     * @var array
     */
    protected $dates = ['closed_at', 'opened_at'];

    /**
     * Event to which the quiz belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * All questions in the quiz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * All teams participated (allowed by admin) for this quiz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'quiz_participations');
    }

    /**
     * All quiz participations in this quiz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(QuizParticipation::class, 'quiz_id');
    }

    /**
     * Update quiz status to active.
     *
     * @return bool
     */
    public function setActive()
    {
        return $this->event->update([
            'started_at' => $this->event->started_at ?? now(),
            'active_quiz_id' => $this->id,
        ]) && $this->update(['opened_at' => now()]);
    }

    /**
     * update quiz status to inactive.
     *
     * @return bool
     */
    public function setInactive()
    {
        return $this->event->update(['active_quiz_id' => null])
            && $this->update(['closed_at' => now()]);
    }

    /**
     * Is the given team allowed for this quiz?
     *
     * @param Team $team
     * @return bool
     */
    public function isTeamAllowed(Team $team)
    {
        return $this->teams->pluck('id')->contains($team->id);
    }

    /**
     * Has the given team exceeded the time limit?
     *
     * @param \App\Team $team
     * @return bool
     */
    public function isTimeLimitExceeded(Team $team)
    {
        $participation = $this->participationByTeam($team);

        if ( ! $participation) {
            return false;
        }

        $timeTaken = optional($participation->started_at)->diffInSeconds(now());
        return $timeTaken > ($this->time_limit + 1 * 60); // Add 1 Minute Extra
    }

    /**
     * Allow the given team for this quiz.
     *
     * @param Team $team
     * @return void
     */
    public function allowTeam(Team $team)
    {
        return $this->teams()->attach($team);
    }

    /**
     * The quiz participation by the given team.
     *
     * @param Team $team
     * @return \App\QuizParticipation|null
     */
    public function participationByTeam(Team $team)
    {
        return $this->participations()->where('team_id', $team->id)->first();
    }

    /**
     * Is the quiz completed by the team?
     *
     * @param Team $team
     * @return bool
     */
    public function isCompletedBy(Team $team)
    {
        return optional($this->participationByTeam($team))->finished_at !== null;
    }

    /**
     * Mutator to implode instructions to a string.
     *
     * @param array|Illuminate\Support\Collection $instructions
     * @return void
     */
    public function setInstructionsAttribute($instructions)
    {
        if ($instructions instanceof Collection) {
            $instructions = $instructions->toArray();
        }
        if (is_array($instructions)) {
            $this->attributes['instructions'] = implode("\n", $instructions);
        }
    }

    /**
     * Accessor to explode retrived string to an array of instrucutons.
     *
     * @param string $instructions
     * @return array
     */
    public function getInstructionsAttribute($instructions)
    {
        if ($instructions) {
            return explode("\n", $instructions);
        }
        return [];
    }

    /**
     * Is Quiz Active?
     *
     * @return bool|null
     */
    public function getIsActiveAttribute()
    {
        if ($this->event_id === null) {
            return null;
        }

        return $this->id === $this->event->active_quiz_id
            && $this->opened_at
            && ! $this->closed_at;
    }

    /**
     * Is the quiz closed for participation?
     *
     * @return bool
     */
    public function getIsClosedAttribute()
    {
        return ! ! $this->closed_at;
    }

    /**
     * Route key name for url generation & model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
