<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Event extends Model
{
    /**
     * The attributes that are NOT mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are of DateTime type, mutated to instance of Carbon.
     *
     * @var array
     */
    protected $dates = ['started_at', 'ended_at'];

    /**
     * The attributes that are appended for array.
     *
     * @var array
     */
    protected $appends = ['isLive', 'hasEnded'];

    /**
     * Teams participating in the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'event_participations');
    }

    /**
     * Quiz currently active in the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeQuiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * The quizzes associated with this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * All members particpating in the event.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allParticipantMembers()
    {
        return $this->loadMissing('teams.members')->teams->flatMap->members;
    }

    /**
     * Is any of the given members participating in the event?
     *
     * @param \Illuminate\Database\Eloquent\Collection $members
     * @return bool
     */
    public function isAnyParticipating(Collection $members)
    {
        $memberIds = $members->pluck('id');
        $participantIds = $this->allParticipantMembers()->pluck('id');

        // or this way?
        // return !!$participantIds->intersect($memberIds)->count();

        return $memberIds->some(function ($memberId) use ($participantIds) {
            return $participantIds->contains($memberId);
        });
    }

    /**
     * Gives Team, through which the given user is participating.
     *
     * @param \App\Models\User $user
     * @return \App\Models\Team|null
     */
    public function participatingTeamByUser(User $user)
    {
        return $this->loadMissing('teams.members')
            ->teams->first(function ($team) use ($user) {
                return $team->members->pluck('id')->contains($user->id);
            });
    }

    /**
     * Can given Team withdraw participation from this event?
     *
     * @param \App\Models\Team $team
     * @return bool
     */
    public function canBeWithdrawn(Team $team)
    {
        if ($this->quizzes->count()) {
            //first quiz is not yet closed and team have not submit their response.
            return ! $this->quizzes->first()->isClosed &&
                ! $this->quizzes->first()->isCompletedBy($team);
        }

        return ! $this->isLive;
    }

    /**
     * Set event live (When event actually begins).
     *
     * @return bool
     */
    public function setLive()
    {
        if ($this->isLive) {
            return true;
        }

        return $this->update(['started_at' => now()]);
    }

    /**
     * End this event (When the event gets over).
     *
     * @return bool
     */
    public function end()
    {
        if ($this->isLive) {
            return $this->update(['ended_at' => now()]);
        }

        return false;
    }

    /**
     * Accessor to access isLive attribute.
     *
     * @return bool
     */
    public function getIsLiveAttribute()
    {
        return $this->hasStarted && ! $this->hasEnded;
    }

    /**
     * Has the event started?
     *
     * @return bool
     */
    public function getHasStartedAttribute()
    {
        return (bool) $this->started_at;
    }

    /**
     * Has the event ended?
     *
     * @return bool
     */
    public function getHasEndedAttribute()
    {
        return (bool) $this->ended_at;
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
