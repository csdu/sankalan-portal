<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are NOT mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * The attributes that are appended for array.
     *
     * @var array<string>
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
     * Is any of the given members participating in the event?
     *
     * @param \Illuminate\Database\Eloquent\Collection $members
     * @return bool
     */
    public function isAnyParticipating(Collection $members)
    {
        $memberIds = $members->pluck('id');
        $participantIds = $this->loadMissing('teams.members')->teams->flatMap->members->pluck('id');

        return !$participantIds->intersect($memberIds)->isEmpty();
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
            ->teams
            ->first(fn ($team) => $team->members->pluck('id')->contains($user->id));
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

        return $this->update([
            'started_at' => Date::now(),
        ]);
    }

    /**
     * Get is live attribute.
     *
     * @return bool
     */
    public function getIsLiveAttribute()
    {
        return !is_null($this->started_at) && is_null($this->ended_at);
    }

    /**
     * Get has ended attribute.
     *
     * @return bool
     */
    public function getHasEndedAttribute()
    {
        return !is_null($this->ended_at);
    }

    /**
     * End an event.
     *
     * @return bool
     */
    public function end()
    {
        return $this->update([
            'ended_at' => Date::now(),
        ]);
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
